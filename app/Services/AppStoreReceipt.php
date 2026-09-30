<?php

namespace App\Services;

/**
 * Verifies a StoreKit 2 signed transaction (JWS) offline: the certificate
 * chain must lead to Apple's pinned root, and the ES256 signature must match.
 * Returns the transaction's claims, or null when anything is off.
 */
class AppStoreReceipt
{
    public function __construct(private readonly string $rootPem) {}

    public static function withAppleRoot(): self
    {
        return new self(file_get_contents(resource_path('certs/AppleRootCA-G3.pem')));
    }

    /** @return array{bundleId:string,productId:string,environment:string,expiresDate:?int,revocationDate:?int,originalTransactionId:string}|null */
    public function verify(string $jws): ?array
    {
        $parts = explode('.', trim($jws));
        if (count($parts) !== 3) {
            return null;
        }
        [$header64, $payload64, $signature64] = $parts;
        $header = json_decode(self::base64url($header64) ?: '', true);
        $payload = json_decode(self::base64url($payload64) ?: '', true);
        $signature = self::base64url($signature64);
        if (! is_array($header) || ! is_array($payload) || $signature === false) {
            return null;
        }
        if (($header['alg'] ?? null) !== 'ES256' || count($header['x5c'] ?? []) < 2) {
            return null;
        }

        $chain = array_map(fn ($der) => "-----BEGIN CERTIFICATE-----\n".chunk_split($der, 64)."-----END CERTIFICATE-----\n", $header['x5c']);
        if (! $this->chainIsTrusted($chain)) {
            return null;
        }
        $leafKey = openssl_pkey_get_public($chain[0]);
        $der = self::rawToDer($signature);
        if (! $leafKey || $der === null
            || openssl_verify("$header64.$payload64", $der, $leafKey, OPENSSL_ALGO_SHA256) !== 1) {
            return null;
        }

        foreach (['bundleId', 'productId', 'environment', 'originalTransactionId'] as $required) {
            if (! isset($payload[$required])) {
                return null;
            }
        }

        return [
            'bundleId' => (string) $payload['bundleId'],
            'productId' => (string) $payload['productId'],
            'environment' => (string) $payload['environment'],
            'expiresDate' => isset($payload['expiresDate']) ? (int) $payload['expiresDate'] : null,
            'revocationDate' => isset($payload['revocationDate']) ? (int) $payload['revocationDate'] : null,
            'originalTransactionId' => (string) $payload['originalTransactionId'],
        ];
    }

    /** Every certificate signed by the next one, ending at Apple's root, all within their dates. */
    private function chainIsTrusted(array $chain): bool
    {
        $root = openssl_x509_read($this->rootPem);
        if (! $root) {
            return false;
        }
        $now = time();
        $certs = [];
        foreach ($chain as $pem) {
            $cert = openssl_x509_read($pem);
            $info = $cert ? openssl_x509_parse($cert) : null;
            if (! $cert || ! $info || $info['validFrom_time_t'] > $now || $info['validTo_time_t'] < $now) {
                return false;
            }
            $certs[] = $cert;
        }
        for ($i = 0; $i < count($certs) - 1; $i++) {
            if (openssl_x509_verify($certs[$i], openssl_pkey_get_public($certs[$i + 1])) !== 1) {
                return false;
            }
        }
        $last = end($certs);
        // The chain may or may not include the root itself; either way it must end at ours.
        openssl_x509_export($last, $lastPem);
        openssl_x509_export($root, $rootPem);
        if ($lastPem === $rootPem) {
            return true;
        }

        return openssl_x509_verify($last, openssl_pkey_get_public($root)) === 1;
    }

    private static function base64url(string $value): string|false
    {
        return base64_decode(strtr($value, '-_', '+/').str_repeat('=', (4 - strlen($value) % 4) % 4), true);
    }

    /** JWS ES256 signatures are r||s (64 bytes); OpenSSL wants ASN.1 DER. */
    private static function rawToDer(string $raw): ?string
    {
        if (strlen($raw) !== 64) {
            return null;
        }
        $encode = function (string $int): string {
            $int = ltrim($int, "\x00");
            if ($int === '' || ord($int[0]) & 0x80) {
                $int = "\x00".$int;
            }

            return "\x02".chr(strlen($int)).$int;
        };
        $body = $encode(substr($raw, 0, 32)).$encode(substr($raw, 32));

        return "\x30".chr(strlen($body)).$body;
    }
}
