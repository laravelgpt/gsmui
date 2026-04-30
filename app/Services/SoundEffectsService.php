<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

/**
 * Sound Effects Service
 * Integrates sound effects for interactive feedback
 */
class SoundEffectsService
{
    /**
     * Available sound effects
     */
    protected $sounds = [
        'click' => [
            'file' => 'sounds/click.mp3',
            'volume' => 0.3,
            'description' => 'Button click sound',
        ],
        'success' => [
            'file' => 'sounds/success.mp3',
            'volume' => 0.5,
            'description' => 'Success notification sound',
        ],
        'error' => [
            'file' => 'sounds/error.mp3',
            'volume' => 0.6,
            'description' => 'Error notification sound',
        ],
        'warning' => [
            'file' => 'sounds/warning.mp3',
            'volume' => 0.5,
            'description' => 'Warning notification sound',
        ],
        'notification' => [
            'file' => 'sounds/notification.mp3',
            'volume' => 0.4,
            'description' => 'General notification sound',
        ],
        'hover' => [
            'file' => 'sounds/hover.mp3',
            'volume' => 0.2,
            'description' => 'Mouse hover sound',
        ],
        'purchase' => [
            'file' => 'sounds/purchase.mp3',
            'volume' => 0.6,
            'description' => 'Purchase complete sound',
        ],
        'payment' => [
            'file' => 'sounds/payment.mp3',
            'volume' => 0.5,
            'description' => 'Payment processing sound',
        ],
        'download' => [
            'file' => 'sounds/download.mp3',
            'volume' => 0.4,
            'description' => 'Download started sound',
        ],
        'upload' => [
            'file' => 'sounds/upload.mp3',
            'volume' => 0.4,
            'description' => 'Upload started sound',
        ],
        'delete' => [
            'file' => 'sounds/delete.mp3',
            'volume' => 0.5,
            'description' => 'Item deleted sound',
        ],
        'add' => [
            'file' => 'sounds/add.mp3',
            'volume' => 0.4,
            'description' => 'Item added sound',
        ],
        'remove' => [
            'file' => 'sounds/remove.mp3',
            'volume' => 0.4,
            'description' => 'Item removed sound',
        ],
        'open' => [
            'file' => 'sounds/open.mp3',
            'volume' => 0.3,
            'description' => 'Menu/modal open sound',
        ],
        'close' => [
            'file' => 'sounds/close.mp3',
            'volume' => 0.3,
            'description' => 'Menu/modal close sound',
        ],
        'type' => [
            'file' => 'sounds/type.mp3',
            'volume' => 0.2,
            'description' => 'Keyboard typing sound',
        ],
        'complete' => [
            'file' => 'sounds/complete.mp3',
            'volume' => 0.7,
            'description' => 'Task completed sound',
        ],
        'level-up' => [
            'file' => 'sounds/level-up.mp3',
            'volume' => 0.6,
            'description' => 'Level up achievement sound',
        ],
        'unlock' => [
            'file' => 'sounds/unlock.mp3',
            'volume' => 0.5,
            'description' => 'Feature unlocked sound',
        ],
    ];

    /**
     * Play a sound effect
     */
    public function play($sound, $options = [])
    {
        if (!isset($this->sounds[$sound])) {
            Log::warning("Sound effect not found: {$sound}");
            return false;
        }

        $config = $this->sounds[$sound];
        
        // Merge options
        $volume = $options['volume'] ?? $config['volume'];
        $loop = $options['loop'] ?? false;
        $rate = $options['rate'] ?? 1.0;

        // Log sound effect play
        Log::info("Playing sound: {$sound}", [
            'volume' => $volume,
            'loop' => $loop,
            'rate' => $rate,
        ]);

        // In a real implementation, this would trigger the actual sound
        // For now, we'll return the sound configuration
        return [
            'sound' => $sound,
            'file' => $config['file'],
            'volume' => $volume,
            'loop' => $loop,
            'rate' => $rate,
            'played_at' => now(),
        ];
    }

    /**
     * Play success sound
     */
    public function success($options = [])
    {
        return $this->play('success', $options);
    }

    /**
     * Play error sound
     */
    public function error($options = [])
    {
        return $this->play('error', $options);
    }

    /**
     * Play warning sound
     */
    public function warning($options = [])
    {
        return $this->play('warning', $options);
    }

    /**
     * Play click sound
     */
    public function click($options = [])
    {
        return $this->play('click', $options);
    }

    /**
     * Play notification sound
     */
    public function notification($options = [])
    {
        return $this->play('notification', $options);
    }

    /**
     * Play hover sound
     */
    public function hover($options = [])
    {
        return $this->play('hover', $options);
    }

    /**
     * Play purchase sound
     */
    public function purchase($options = [])
    {
        return $this->play('purchase', $options);
    }

    /**
     * Play payment sound
     */
    public function payment($options = [])
    {
        return $this->play('payment', $options);
    }

    /**
     * Play download sound
     */
    public function download($options = [])
    {
        return $this->play('download', $options);
    }

    /**
     * Play upload sound
     */
    public function upload($options = [])
    {
        return $this->play('upload', $options);
    }

    /**
     * Get all available sounds
     */
    public function getAvailableSounds()
    {
        return $this->sounds;
    }

    /**
     * Check if sound exists
     */
    public function hasSound($sound)
    {
        return isset($this->sounds[$sound]);
    }

    /**
     * Get sound configuration
     */
    public function getSoundConfig($sound)
    {
        return $this->sounds[$sound] ?? null;
    }

    /**
     * Add custom sound
     */
    public function addSound($name, $config)
    {
        $this->sounds[$name] = array_merge([
            'file' => '',
            'volume' => 0.5,
            'description' => '',
        ], $config);

        return true;
    }

    /**
     * Remove custom sound
     */
    public function removeSound($name)
    {
        if ($this->hasSound($name)) {
            unset($this->sounds[$name]);
            return true;
        }
        return false;
    }

    /**
     * Play multiple sounds in sequence
     */
    public function playSequence(array $sounds, $delay = 100)
    {
        $results = [];
        foreach ($sounds as $sound) {
            if (is_string($sound)) {
                $results[] = $this->play($sound);
            } elseif (is_array($sound) && isset($sound['name'])) {
                $results[] = $this->play($sound['name'], $sound['options'] ?? []);
            }
            
            // Simulate delay (in real implementation, this would be handled by frontend)
            usleep($delay * 1000);
        }
        return $results;
    }

    /**
     * Play random sound from category
     */
    public function playRandom($category = null)
    {
        if ($category) {
            // Filter by category (if we had categories)
            $sounds = $this->sounds;
        } else {
            $sounds = $this->sounds;
        }

        $randomSound = array_rand($sounds);
        return $this->play($randomSound);
    }

    /**
     * Get sound statistics
     */
    public function getStatistics()
    {
        $totalPlays = Cache::get('sound_total_plays', 0);
        $soundPlays = Cache::get('sound_plays', []);

        return [
            'total_plays' => $totalPlays,
            'sound_plays' => $soundPlays,
            'available_sounds' => count($this->sounds),
            'most_played' => $this->getMostPlayedSound(),
        ];
    }

    /**
     * Get most played sound
     */
    protected function getMostPlayedSound()
    {
        $soundPlays = Cache::get('sound_plays', []);
        
        if (empty($soundPlays)) {
            return null;
        }

        arsort($soundPlays);
        $mostPlayed = key($soundPlays);
        
        return [
            'sound' => $mostPlayed,
            'plays' => $soundPlays[$mostPlayed],
        ];
    }

    /**
     * Record sound play
     */
    protected function recordPlay($sound)
    {
        // Increment total plays
        $totalPlays = Cache::get('sound_total_plays', 0);
        Cache::put('sound_total_plays', $totalPlays + 1, now()->addDays(30));

        // Increment sound-specific plays
        $soundPlays = Cache::get('sound_plays', []);
        $soundPlays[$sound] = ($soundPlays[$sound] ?? 0) + 1;
        Cache::put('sound_plays', $soundPlays, now()->addDays(30));
    }

    /**
     * Get audio HTML5 player code
     */
    public function getAudioPlayer($sound, $options = [])
    {
        if (!$this->hasSound($sound)) {
            return '';
        }

        $config = $this->sounds[$sound];
        $volume = $options['volume'] ?? $config['volume'];
        $loop = $options['loop'] ?? false;

        return <<< HTML
<audio controls preload="none" style="display: none;" id="sound-{$sound}">
    <source src="{$config['file']}" type="audio/mpeg">
    Your browser does not support the audio element.
</audio>
<script>
    function playSound_{$sound}() {
        var audio = document.getElementById('sound-{$sound}');
        audio.volume = {$volume};
        audio.loop = {$loop ? 'true' : 'false'};
        audio.play().catch(e => console.log('Audio play failed:', e));
    }
</script>
HTML;
    }

    /**
     * Generate JavaScript for sound effects
     */
    public function generateJavaScript()
    {
        $sounds = json_encode(array_keys($this->sounds));
        
        return <<< JS
// Sound Effects System
class SoundEffects {
    constructor() {
        this.sounds = {$sounds};
        this.enabled = true;
        this.volume = 0.5;
        this.audioContext = null;
        this.soundBuffers = {};
    }

    init() {
        try {
            this.audioContext = new (window.AudioContext || window.webkitAudioContext)();
        } catch (e) {
            console.warn('Web Audio API not supported');
        }
    }

    play(sound, options = {}) {
        if (!this.enabled || !this.sounds.includes(sound)) {
            return;
        }

        const volume = options.volume || this.volume;
        const loop = options.loop || false;

        // Create audio element
        const audio = new Audio(`/sounds/${sound}.mp3`);
        audio.volume = volume;
        audio.loop = loop;
        
        audio.play().catch(e => {
            console.log('Sound play failed (may be blocked by browser):', e);
        });

        return audio;
    }

    // Convenience methods
    click(options) { return this.play('click', options); }
    success(options) { return this.play('success', options); }
    error(options) { return this.play('error', options); }
    warning(options) { return this.play('warning', options); }
    notification(options) { return this.play('notification', options); }
    hover(options) { return this.play('hover', options); }
    purchase(options) { return this.play('purchase', options); }
    payment(options) { return this.play('payment', options); }
    download(options) { return this.play('download', options); }
    upload(options) { return this.play('upload', options); }
    complete(options) { return this.play('complete', options); }
    levelUp(options) { return this.play('level-up', options); }
    unlock(options) { return this.play('unlock', options); }

    enable() { this.enabled = true; }
    disable() { this.enabled = false; }
    setVolume(volume) { this.volume = Math.max(0, Math.min(1, volume)); }
}

// Initialize sound effects
const soundEffects = new SoundEffects();
soundEffects.init();

// Auto-initialize on user interaction
document.addEventListener('click', () => soundEffects.init(), { once: true });
JS;
    }
}
