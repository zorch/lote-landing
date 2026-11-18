"""Generates Lote's music library and sound effects with ElevenLabs.

Reads the key from ~/.config/lote/elevenlabs.key (never stored in the repo).
Skips files that already exist, so it can be re-run to resume or to add more.
Writes public/audio/manifest.json, which the apps read.
"""
import json, pathlib, sys, time, urllib.request, urllib.error
from concurrent.futures import ThreadPoolExecutor

ROOT = pathlib.Path(__file__).resolve().parent.parent / "public" / "audio"
KEY = (pathlib.Path.home() / ".config/lote/elevenlabs.key").read_text().strip()
BED = "Instrumental, no vocals, mixed to sit under a person talking, steady energy without big drops or silences, clean ending."
# Richer bed for the deep moods: real instruments, body, natural dynamics, no noise textures.
DEEP_BED = ("Instrumental only, no vocals, no rain, no vinyl crackle, no noise textures. Rich, full-bodied, warm low end, "
            "real-sounding instruments with natural dynamics. Designed to sit under a person speaking to camera: "
            "no silences, no abrupt drops, natural ending.")
# Seconds per mood. New tracks are 2 minutes: most videos are ~1 minute, and the apps
# cross-fade a repeat when a video is longer. The first reflective batch is 3 minutes.
LENGTH = {"reflective": 180}  # inspiring-5/6 and soft-5/6 are also 180 s (moved from reflective)
DEFAULT_LENGTH = 120

# Mood ids match MusicGenerator.Mood in both apps.
MUSIC = {
    "soft": ["Soft acoustic guitar and gentle piano, warm and calm, light brushed percussion",
             "Gentle ambient pads with soft piano notes, peaceful and clear",
             "Light acoustic folk with fingerpicked guitar and soft shaker",
             "Warm minimal piano with subtle strings, intimate and calm",
                       "Warm vintage waltz in 3/4 at 100 BPM: pizzicato strings, clarinet melody, muted trumpet, piano and light brushes, swinging gently, major key with a bittersweet minor middle section. Charming, nostalgic, tender.",
                       "Intimate fingerpicked acoustic guitar at 74 BPM with a bright capo tone, soft cello, gentle piano notes, folk ballad in a major key with a wistful vi chord (I–V–vi–IV), tiny brushed percussion. Tender, honest, hopeful.",
        # Nov 2026: originals in the genres trending on TikTok and Reels.
        "Warm neo-soul lullaby at 78 BPM: Rhodes electric piano with gentle tremolo playing jazzy major seventh chords (Imaj7–iii7–IVmaj7–V7), round fingered bass guitar, soft brushed kit with rim clicks, a nylon guitar answering the piano with small melodic phrases, light vibraphone sparkles. Tender, cozy, a parent talking softly at bedtime.",
        "Sunlit indie folk at 92 BPM: two fingerpicked acoustic guitars in open tuning, warm upright piano doubling the melody, soft kick on one and three, hand claps and tambourine that come in halfway, low cello pad, wordless hummed-style melody played on a mellow flugelhorn. Major key (I–IV–vi–V). Nostalgic home-video warmth, hopeful, family.",
    ],
    "energy": ["Upbeat pop with bright synths, claps and driving drums, exciting",
               "Energetic indie rock with punchy drums and electric guitar riffs",
               "Bright electro pop with bouncy bass and snappy percussion",
               "Fast funky groove with slap bass, brass stabs and tight drums",
        # Nov 2026: originals in the genres trending on TikTok and Reels.
        "Latin urban pop at 96 BPM with a dembow-style drum groove: syncopated plucked nylon guitar riff, bright marimba-like synth plucks, deep round sub bass sliding between notes, crisp rimshots, shakers and timbales fills, airy pad in the chorus sections, minor key (i–VI–III–VII). Sunny, danceable, confident, modern radio polish.",
        "Glossy dance-pop at 122 BPM: four-on-the-floor kick with tight claps, rubbery funk bass line, chopped rhythm guitar stabs, shimmering analog synth chords with sidechain pumping, sparkling arpeggio on top, big filtered build into each chorus section. Major key with a bittersweet vi chord. Bright, stylish, irresistible groove.",
    ],
    # Original bases (chord loop, tempo, instruments, feel) in the spirit of the
    # reflective songs that go viral on TikTok and Instagram. No song is copied.
    "reflective": [
        "Slow waltz in 6/8 at 72 BPM on a minor loop (i–VI–III–VII). Tremolo electric guitar drenched in spring reverb, upright bass, brushed snare, distant pedal steel swells and a ghostly choir pad. Nostalgic, heartbroken, cinematic.",
        "Muted felt piano chords on a slow four-chord minor loop (vi–IV–I–V) at 80 BPM, soft synth pads, deep sub bass, light finger snaps, subtle string swells. Intimate, autumnal, bittersweet storytelling.",
        "Dream pop ballad in 6/8 at 68 BPM: washed-out reverb guitars, slow drums with big room reverb, lush string section, major key with aching suspended chords (I–vi–IV–V). Overwhelming emotion, swelling bigger in the middle section while staying smooth.",
        "Music-box glockenspiel and clean electric guitar arpeggio lullaby, slow 4/4 at 76 BPM, gentle bass and soft brushed drums, major key with melancholic minor turns (I–vi–IV–iv). Fragile, comforting, sad and sweet.",
        "Dream pop at 72 BPM: slide guitar descending melody, vintage organ chords, warm fuzz bass, soft drum machine, long reverb tails, minor-leaning loop (i–VII–VI–VII). Floating, nostalgic, spacious.",
        "Dark alternative ballad at 70 BPM: clean electric guitar arpeggio in a minor key (i–VI–III–VII), deep sub bass, sparse snare, soft pad, moody and vulnerable, with a little more emotion toward the end.",
        "Lo-fi ambient piano at 60 BPM, slowed and reverb-washed, two alternating minor chords with a simple top-note melody, soft sub bass, no drums, clean and pure. Calm, lonely, introspective.",
        "Solo grand piano, dramatic romantic ballad at 84 BPM, flowing minor-key arpeggios with a soaring right-hand melody, rubato swells and a light string pad underneath for depth. Deeply emotional, passionate, cinematic.",
    ],
    "inspiring": ["Inspiring cinematic build with piano, strings and steady drums, hopeful",
                  "Uplifting corporate acoustic with claps and glockenspiel, positive",
                  "Motivational orchestral pop with big drums and piano, rising",
                  "Hopeful indie with guitars, piano and soft driving beat",
                       "Driving piano sixteenth-note arpeggios cycling three modal chords (I–v–ii), 130 BPM with a half-time feel, warm bass pulse, soft kick and a string pad rising under the piano. Hopeful urgency, emotional, builds gradually.",
                       "Emotional cinematic build at 76 BPM: felt piano intro, strings enter, soft taiko and cello swell to a big heartfelt climax with full orchestra, then settle gently. Minor key (i–VI–III–VII). For moving, inspiring moments.",
        # Nov 2026: originals in the genres trending on TikTok and Reels.
        "Anthemic indie pop at 104 BPM: chiming delayed electric guitars, driving floor-tom groove with big claps, warm synth bass, piano octaves lifting the chorus, gang-style wordless chant melody played by layered synth brass, strings rising in the last third. Major key (IV–I–V–vi). Wide open, triumphant, golden hour, things are getting better.",
        "Modern cinematic piano pop at 88 BPM: intimate felt piano motif that repeats and grows, warm analog pad, pulsing muted guitar eighth notes, deep soft kick, live strings joining in layers, gentle snare rolls into a full emotional lift, then a calm resolved ending. Major key with suspended chords. Heartfelt, encouraging, a lesson learned.",
    ],
    "playful": ["Playful quirky ukulele with whistles, claps and pizzicato, fun",
                "Bouncy comedic cartoon music with bassoon and pizzicato strings",
                "Happy funky groove with marimba and claps, cheerful",
                "Light quirky electronic with plucky synths and toy percussion",
        # Nov 2026: originals in the genres trending on TikTok and Reels.
        "Cheeky funk at 110 BPM led by a bold tenor saxophone riff that gets a little louder and more over-the-top every eight bars: tight clavinet, slap bass, dry punchy drums with cowbell, brass section stabs answering the sax, wah guitar scratches, comedic stop-time breaks that land right back on the groove. Mischievous, funny, confident.",
    ],
    "tense": ["Suspenseful dark synths with ticking percussion and low drones",
              "Tense cinematic strings with pulsing bass, mysterious",
              "Dark minimal electronic with heartbeat kick and eerie pads",
              "Investigative documentary tension with piano ostinato and low strings",
        # Nov 2026: originals in the genres trending on TikTok and Reels.
        "Melodic techno at 124 BPM in a dark minor key: rolling sixteenth-note bass arpeggio with slow filter movement, steady punchy kick, crisp offbeat hi-hats, cold evolving pad, a haunting three-note bell motif echoing with long delay, low string drone underneath, tension that keeps tightening without ever breaking. Serious, investigative, breaking news at night.",
    ],
    "business": ["Modern corporate tech background with clean punchy drums, warm bass and plucked synths, confident",
                 "Smooth business hip hop beat with mellow keys and crisp drums, professional",
                 "Upbeat startup vibe with marimba, claps and light electronic beat, motivating",
                 "Minimal confident electronic with deep bass and clean percussion, finance news style",
        # Nov 2026: originals in the genres trending on TikTok and Reels.
        "Sleek deep house at 118 BPM: warm round kick, soft shuffled hats, smooth Rhodes chord stabs with jazzy ninths, rubbery sub bass groove, muted guitar plucks, airy vocal-like pad without words, tasteful filter sweeps. Minor seventh loop (ii7–V7–Imaj7–vi7). Hypnotic, smooth, self-assured, modern newsroom and city at dusk.",
        "Modern news-explainer beat at 100 BPM: tight boom-bap drums with crisp snare, warm upright bass line, staccato string ostinato, clean electric piano chords, subtle glassy synth pulse marking the eighth notes, short brass swells at phrase endings. Minor key, confident and forward-moving. Smart, credible, here is what happened today.",
    ],
    "hype": ["High energy trap beat with heavy 808s and hi-hats, powerful",
             "Epic sports hype with big drums, distorted bass and synth stabs",
             "Energetic EDM build with driving four on the floor kick, exciting",
             "Aggressive phonk style beat with cowbell and heavy bass",
        # Nov 2026: originals in the genres trending on TikTok and Reels.
        "Afro house at 122 BPM: deep kick with rolling log drums and layered congas, shakers and rim patterns, hypnotic marimba ostinato, wide warm sub bass, call-and-response synth brass stabs, rising filter builds into bigger percussion sections. Minor key. Powerful, tribal, euphoric, body-moving.",
        "Club beat at 140 BPM with a bouncing triplet kick pattern: chopped syncopated percussion, bright bell lead playing a catchy four-bar hook, heavy gliding 808 bass, snappy claps on every beat in the build sections, pitched synth stabs, fast hi-hat rolls. Major-leaning, cheeky, viral dance challenge energy.",
    ],
    "chill": ["Chill lo-fi hip hop with dusty drums, mellow electric piano and warm bass, relaxed",
              "Laid back tropical house with soft marimba and smooth groove",
              "Smooth jazzy chillhop with soft saxophone and brushed drums",
              "Relaxed bossa nova with nylon guitar and light percussion",
        # Nov 2026: originals in the genres trending on TikTok and Reels.
        "Cozy coffee-shop soul at 84 BPM: warm Wurlitzer electric piano comping, laid-back pocket drums with soft ghost notes, melodic fingered bass, clean jazz guitar octaves carrying the tune, muted trumpet answering with short phrases, gentle Hammond organ swells. Major seventh loop (Imaj7–vi7–ii7–V7). Morning ritual, smooth, unhurried, feels like sunlight on a table.",
        "Modern jazz quartet at 96 BPM in a gentle bossa-leaning groove: lyrical tenor saxophone melody with a breathy warm tone, clean hollow-body guitar chords and countermelody, upright bass walking softly, brushed drums with light ride cymbal. Mediterranean sunset mood, major key with a wistful minor turn. Elegant, relaxed, golden.",
    ],
    "epic": ["Epic cinematic orchestral with huge drums and brass, powerful and grand",
             "Heroic trailer music with choir pads, strings and big percussion",
             "Dramatic emotional orchestral build with piano and strings",
             "Powerful hybrid orchestral with synth pulses and taiko drums"],
}

# (file, prompt, seconds). Original sounds in the style of popular edits; none copies a real meme.
SFX = [
    ("whoosh-1", "Quick clean swoosh transition, airy and modern", 0.6),
    ("whoosh-2", "Fast deep whoosh pass-by for a video cut", 0.7),
    ("whoosh-3", "Short swipe swish sound, crisp", 0.5),
    ("pop-1", "Short soft pop click for text appearing, satisfying", 0.5),
    ("pop-2", "Bubbly cartoon pop, cute and quick", 0.5),
    ("click", "Crisp UI mouse click", 0.5),
    ("impact-1", "Deep cinematic hit impact, punchy with short tail", 1.2),
    ("boom", "Deep heavy bass boom meme-style hit with reverb", 1.5),
    ("riser", "Short tension riser swell that rises then ends abruptly", 1.5),
    ("glitch", "Short digital glitch stutter transition", 0.6),
    ("record-scratch", "Vinyl record scratch stop", 0.8),
    ("cash-register", "Cash register ka-ching with bell, money sound", 1.2),
    ("coins", "Pile of coins falling and jingling", 1.2),
    ("money-count", "Fast bill counting machine flipping banknotes", 1.5),
    ("coin-single", "Single bright video game coin pickup chime", 0.5),
    ("ding-correct", "Bright correct answer ding, positive", 0.8),
    ("buzzer-wrong", "Game show wrong answer buzzer", 0.9),
    ("fail", "Comedic sad trombone wah wah fail", 1.8),
    ("sparkle", "Magical sparkle twinkle shimmer", 1.0),
    ("heart", "Cute soft heart pop with gentle chime, love", 0.8),
    ("level-up", "Short video game level up jingle", 1.2),
    ("notification", "Phone notification ding, clean", 0.6),
    ("camera-shutter", "Camera shutter click snapshot", 0.5),
    ("typing", "Fast keyboard typing burst", 1.0),
    ("swoosh-up", "Rising swoosh going up, growth", 0.8),
    ("swoosh-down", "Falling swoosh going down, drop", 0.8),
    ("bass-drop", "Short punchy bass drop hit", 1.2),
    ("crowd-wow", "Small crowd saying wow in amazement", 1.2),
    ("crowd-laugh", "Short audience laugh track", 1.8),
    ("applause", "Short enthusiastic applause", 2.0),
    ("drumroll", "Short snare drumroll ending with cymbal crash", 2.0),
    ("tada", "Triumphant short ta-da fanfare", 1.2),
    ("suspense-hit", "Dramatic suspense sting dun dun dun", 1.8),
    ("tick", "Clock ticking fast for urgency", 1.5),
    ("bell", "Clear boxing bell ding", 1.0),
    ("phone-vibrate", "Phone vibrating on a table buzz", 1.0),
    ("magic", "Magic wand reveal shimmer", 1.0),
    ("slide", "Smooth slide transition swipe", 0.5),
]

def post(url, body, path, attempts=6):
    for attempt in range(attempts):
        request = urllib.request.Request(url, data=json.dumps(body).encode(), headers={"xi-api-key": KEY, "Content-Type": "application/json"})
        try:
            with urllib.request.urlopen(request, timeout=300) as response:
                data = response.read()
            path.write_bytes(data)
            return f"ok {path.name} {len(data)//1024} KB"
        except urllib.error.HTTPError as error:
            if error.code == 429:
                time.sleep(10 * (attempt + 1)); continue
            return f"error {path.name}: {error.code} {error.read()[:200]}"
        except Exception as error:
            time.sleep(5); last = error
    return f"error {path.name}: reintentos agotados"

def jobs():
    for mood, prompts in MUSIC.items():
        for index, prompt in enumerate(prompts, 1):
            path = ROOT / "music" / f"{mood}-{index}.mp3"
            if not path.exists():
                # The long, detailed prompts get the richer bed.
                bed = DEEP_BED if mood in LENGTH or len(prompt) > 150 else BED
                yield ("https://api.elevenlabs.io/v1/music?output_format=mp3_44100_128",
                       {"prompt": f"{prompt} {bed}", "music_length_ms": LENGTH.get(mood, DEFAULT_LENGTH) * 1000, "force_instrumental": True}, path)
    for name, prompt, seconds in SFX:
        path = ROOT / "sfx" / f"{name}.mp3"
        if not path.exists():
            yield ("https://api.elevenlabs.io/v1/sound-generation?output_format=mp3_44100_128",
                   {"text": prompt, "duration_seconds": seconds, "prompt_influence": 0.6}, path)

def normalize():
    """Every track at the same loudness (-18 LUFS) in music/v2, so the apps' volume means the same for all."""
    import subprocess
    (ROOT / "music" / "v2").mkdir(exist_ok=True)
    for source in sorted((ROOT / "music").glob("*.mp3")):
        target = ROOT / "music" / "v2" / source.name
        if not target.exists():
            subprocess.run(["ffmpeg", "-v", "error", "-y", "-i", str(source), "-af", "loudnorm=I=-18:TP=-2:LRA=11",
                            "-ar", "44100", "-b:a", "160k", str(target)], check=True)

def manifest():
    music = {mood: [f"music/v2/{mood}-{i}.mp3" for i in range(1, len(p) + 1) if (ROOT / "music" / "v2" / f"{mood}-{i}.mp3").exists()] for mood, p in MUSIC.items()}
    sfx = {name: f"sfx/{name}.mp3" for name, _, _ in SFX if (ROOT / "sfx" / f"{name}.mp3").exists()}
    (ROOT / "manifest.json").write_text(json.dumps({"version": 2, "music": music, "sfx": sfx}, indent=2))

if __name__ == "__main__":
    (ROOT / "music").mkdir(parents=True, exist_ok=True); (ROOT / "sfx").mkdir(parents=True, exist_ok=True)
    todo = list(jobs())
    # An optional first argument limits how many are generated in this run.
    if len(sys.argv) > 1: todo = todo[:int(sys.argv[1])]
    print(f"{len(todo)} por generar", flush=True)
    # The Creator plan allows 2 requests at a time.
    with ThreadPoolExecutor(max_workers=2) as pool:
        for line in pool.map(lambda job: post(*job), todo):
            print(line, flush=True)
    normalize()
    manifest()
    print("manifest listo", flush=True)
