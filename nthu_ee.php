<?php

declare(strict_types=1);

final class nthu_ee extends rcube_plugin
{
    /**
     * {@inheritdoc}
     */
    public $task = '';

    /**
     * The loaded configuration.
     *
     * @var rcube_config
     */
    private $config;

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        $this->load_plugin_config();

        // the current skin name like from `$this->config->get('skin')` is not much
        // helpful for extended skins, we try to get it's base skin name directly
        $base_skin = $this->get_base_skin_name();

        $this->add_texts('locales', true);

        $this
            ->add_plugin_assets($base_skin)
            ->add_plugin_buttons($base_skin);
    }

    /**
     * Add plugin assets.
     *
     * @param string $skin the skin name
     */
    private function add_plugin_assets(string $skin): self
    {
        $this->include_stylesheet("skins/{$skin}/main.css");
        $this->include_script('js/main.min.js');

        return $this;
    }

    /**
     * Add plugin buttons.
     *
     * @param string $skin the skin name
     */
    private function add_plugin_buttons(string $skin): self
    {
        $this->add_plugin_buttons_loginfooter([
            [
                'label' => "{$this->ID}.old_webmail",
                'title' => "{$this->ID}.old_webmail_never_receive_new_mail",
                'href' => 'https://rcmail.ee.nthu.edu.tw',
                'badgeType' => 'danger',
            ], [
                'label' => "{$this->ID}.manual",
                'title' => "{$this->ID}.open_manual",
                'href' => 'skins/.manual/',
                'target' => '_blank',
            ], [
                'label' => "{$this->ID}.department_website",
                'title' => "{$this->ID}.open_department_website",
                'href' => 'http://web.ee.nthu.edu.tw',
                'target' => '_blank',
            ],
        ], $skin);

        $this->add_plugin_buttons_taskbar([
            [
                'label' => "{$this->ID}.manual",
                'title' => "{$this->ID}.open_manual",
                'href' => 'skins/.manual/',
                'target' => '_blank',
            ],
        ], $skin);

        return $this;
    }

    /**
     * Add the plugin buttons to loginfooter.
     *
     * @param array  $btns the buttons
     * @param string $skin the skin name
     */
    private function add_plugin_buttons_loginfooter(array $btns, string $skin): self
    {
        $btns = \array_map(function (array $btn) use ($skin): array {
            $btn['type'] = 'link';
            $btn['classArray'] = $this->class_string_to_array($btn['class'] ?? '');
            $btn['innerclassArray'] = $this->class_string_to_array($btn['innerclass'] ?? '');
            $btn['badgeType'] = $btn['badgeType'] ?? 'secondary';

            // should always has 'support-link' class
            $btn['classArray'][] = 'support-link';

            if ($skin === 'elastic') {
                $btn['classArray'][] = 'badge';
                $btn['classArray'][] = "badge-{$btn['badgeType']}";
                $btn['data-toggle'] = $btn['data-toggle'] ?? 'tooltip';
            }

            $btn['class'] = $this->class_array_to_string($btn['classArray']);
            $btn['innerclass'] = $this->class_array_to_string($btn['innerclassArray']);

            return $btn;
        }, $btns);

        foreach ($btns as $btn) {
            $this->add_button($btn, 'loginfooter');
        }

        return $this;
    }

    /**
     * Add the plugin buttons to taskbar.
     *
     * @param array  $btns the buttons
     * @param string $skin the skin name
     */
    private function add_plugin_buttons_taskbar(array $btns, string $skin): self
    {
        $btns = \array_map(function (array $btn) use ($skin): array {
            $btn['type'] = 'link';
            $btn['classArray'] = $this->class_string_to_array($btn['class'] ?? '');
            $btn['innerclassArray'] = $this->class_string_to_array($btn['innerclass'] ?? '');

            switch ($skin) {
                case 'classic':
                    $btn['classArray'][] = 'button-nthu-ee';
                    break;
                case 'elastic':
                    $btn['classArray'][] = 'nthu-ee';
                    $btn['classArray'][] = 'manual';
                    $btn['innerclassArray'][] = 'inner';
                    break;
                case 'larry':
                    $btn['classArray'][] = 'button-nthu-ee';
                    $btn['innerclassArray'][] = 'button-inner';
                    break;
                default:
                    break;
            }

            $btn['class'] = $this->class_array_to_string($btn['classArray']);
            $btn['innerclass'] = $this->class_array_to_string($btn['innerclassArray']);

            return $btn;
        }, $btns);

        foreach ($btns as $btn) {
            $this->add_button($btn, 'taskbar');
        }

        return $this;
    }

    /**
     * Convert the array of classes to HTML class attribute.
     *
     * @param array $class_array the array of classes
     */
    private function class_array_to_string(array $class_array): string
    {
        return \implode(' ', $this->class_string_to_array(\implode(' ', $class_array)));
    }

    /**
     * Convert the HTML class attribute to an array of classes.
     *
     * @param string $class_string the HTML class attribute
     */
    private function class_string_to_array(string $class_string): array
    {
        return \preg_match_all('/[^\s]++/uS', $class_string, $matches)
            ? \array_keys(\array_count_values($matches[0])) // fast array unique
            : [];
    }

    /**
     * Load plugin configuration.
     */
    private function load_plugin_config(): void
    {
        $rcmail = rcmail::get_instance();

        $this->load_config('config.inc.php.dist');
        $this->load_config('config.inc.php');

        $this->config = $rcmail->config;
    }

    /**
     * Get the lowercase base skin name for the current skin.
     *
     * @return string The base skin name. Empty if none.
     */
    private function get_base_skin_name(): string
    {
        static $base_skins = ['classic', 'larry', 'elastic'];

        $rcube = rcube::get_instance();

        // information about current skin and extended skins (if any)
        $skins = (array) $rcube->output->skins;

        foreach ($base_skins as $base_skin) {
            if (isset($skins[$base_skin])) {
                return $base_skin;
            }
        }

        return '';
    }
}
