<?php

namespace Tests\Unit;

use App\Services\AppStoreReceipt;
use PHPUnit\Framework\TestCase;

/** A root → intermediate → leaf chain made here, so the real crypto path runs. */
class AppStoreReceiptTest extends TestCase
{
    private array $chain = [];   // PEM certs, leaf first

    private string $leafKeyPem = '';

    private string $rootPem = '';

    protected function setUp(): void
    {
        parent::setUp();
        [$this->chain, $this->leafKeyPem, $this->rootPem] = self::makeChain();
    }

    /** @return array{0: string[], 1: string, 2: string} chain (leaf first), leaf private key, root cert */
    private static function makeChain(): array
    {
        $dir = sys_get_temp_dir().'/receipt-'.bin2hex(random_bytes(4));
        mkdir($dir);
        $run = function (string $command) use ($dir): void {
            exec("cd $dir && $command 2>&1", $output, $code);
            if ($code !== 0) {
                throw new \RuntimeException("openssl failed: $command\n".implode("\n", $output));
            }
        };
        $run('openssl ecparam -name prime256v1 -genkey -noout -out root.key');
        $run('openssl req -x509 -new -key root.key -sha256 -days 365 -subj "/CN=Test Root" -out root.pem');
        $run('openssl ecparam -name prime256v1 -genkey -noout -out mid.key');
        $run('openssl req -new -key mid.key -subj "/CN=Test Intermediate" -out mid.csr');
        $run('printf "basicConstraints=CA:TRUE\n" > ca.ext && openssl x509 -req -in mid.csr -CA root.pem -CAkey root.key -CAcreateserial -days 365 -sha256 -extfile ca.ext -out mid.pem');
        $run('openssl ecparam -name prime256v1 -genkey -noout -out leaf.key');
        $run('openssl req -new -key leaf.key -subj "/CN=Test Leaf" -out leaf.csr');
        $run('openssl x509 -req -in leaf.csr -CA mid.pem -CAkey mid.key -CAcreateserial -days 365 -sha256 -out leaf.pem');
        $chain = [file_get_contents("$dir/leaf.pem"), file_get_contents("$dir/mid.pem"), file_get_contents("$dir/root.pem")];
        $result = [$chain, file_get_contents("$dir/leaf.key"), file_get_contents("$dir/root.pem")];
        array_map('unlink', glob("$dir/*"));
        rmdir($dir);

        return $result;
    }

    private function sign(array $payload, ?string $keyPem = null, ?array $chain = null): string
    {
        $key = openssl_pkey_get_private($keyPem ?? $this->leafKeyPem);
        $x5c = array_map(fn ($pem) => str_replace(["-----BEGIN CERTIFICATE-----", "-----END CERTIFICATE-----", "\n"], '', $pem), $chain ?? $this->chain);
        $encode = fn (array $data) => rtrim(strtr(base64_encode(json_encode($data)), '+/', '-_'), '=');
        $signingInput = $encode(['alg' => 'ES256', 'x5c' => $x5c]).'.'.$encode($payload);
        openssl_sign($signingInput, $der, $key, OPENSSL_ALGO_SHA256);
        // DER → raw r||s, like Apple sends it.
        $r = self::derInt($der, 2);                 // SEQUENCE(2 bytes) then INTEGER r
        $s = self::derInt($der, 4 + ord($der[3]));  // INTEGER s follows r

        return $signingInput.'.'.rtrim(strtr(base64_encode($r.$s), '+/', '-_'), '=');
    }

    private static function derInt(string $der, int $offset): string
    {
        $length = ord($der[$offset + 1]);
        $int = substr($der, $offset + 2, $length);

        return str_pad(ltrim($int, "\x00"), 32, "\x00", STR_PAD_LEFT);
    }

    private function payload(): array
    {
        return ['bundleId' => 'com.applote.lote', 'productId' => 'com.applote.lote.pro.yearly', 'environment' => 'Production',
            'originalTransactionId' => '1000', 'expiresDate' => (time() + 86400) * 1000];
    }

    public function test_accepts_a_receipt_signed_by_the_chain(): void
    {
        $claims = (new AppStoreReceipt($this->rootPem))->verify($this->sign($this->payload()));
        $this->assertSame('com.applote.lote.pro.yearly', $claims['productId']);
        $this->assertSame('com.applote.lote', $claims['bundleId']);
        $this->assertNull($claims['revocationDate']);
    }

    public function test_rejects_a_tampered_payload(): void
    {
        $jws = $this->sign($this->payload());
        [$h, $p, $s] = explode('.', $jws);
        $forged = rtrim(strtr(base64_encode(json_encode($this->payload() + ['x' => 1])), '+/', '-_'), '=');
        $this->assertNull((new AppStoreReceipt($this->rootPem))->verify("$h.$forged.$s"));
    }

    public function test_rejects_a_chain_from_another_root(): void
    {
        [$otherChain, $otherKey] = self::makeChain();
        $jws = $this->sign($this->payload(), $otherKey, $otherChain);
        $this->assertNull((new AppStoreReceipt($this->rootPem))->verify($jws));
        // Sanity: the other chain is fine against its own root.
        $this->assertNotNull((new AppStoreReceipt($otherChain[2]))->verify($jws));
    }

    public function test_rejects_garbage(): void
    {
        $verifier = new AppStoreReceipt($this->rootPem);
        $this->assertNull($verifier->verify('abc'));
        $this->assertNull($verifier->verify('a.b.c'));
        $this->assertNull($verifier->verify(''));
    }

    public function test_apple_root_loads(): void
    {
        $pem = file_get_contents(__DIR__.'/../../resources/certs/AppleRootCA-G3.pem');
        $info = openssl_x509_parse($pem);
        $this->assertSame('Apple Root CA - G3', $info['subject']['CN']);
    }
}
