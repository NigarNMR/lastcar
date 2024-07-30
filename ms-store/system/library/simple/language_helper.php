<?php
class LanguageHelper {
    private $languages = null;
    private $currentLanguage = '';
    private $data = array();
    private $clearedData = array();
    private $replace = array(
        'ru' => 'russian',
        'ru_ru' => 'russian',
        'en' => 'english',
        'en-gb' => 'english',
        'en_gb' => 'english'
    );

    public function __construct($languages, $currentLanguage) {
        $this->languages = $languages;
        $this->currentLanguage = $currentLanguage ? $currentLanguage : 'en';

        foreach ($languages as $language) {
            if (!empty($language['directory'])) {
                if ($language['directory'] == 'default' || is_array($language['directory'])) {
                    $language['directory'] = $this->replace[$this->currentLanguage];
                }

                $file = DIR_LANGUAGE . $language['directory'] . '/module/simple.php';
            } else {
                $file = DIR_LANGUAGE . $language['code'] . '/module/simple.php';

                if (!file_exists($file) && isset($this->replace[$currentLanguage])) {
                    $file = DIR_LANGUAGE . $this->replace[$currentLanguage] . '/module/simple.php';
                }
            }

            if (file_exists($file)) {
                $_ = array();
                $this->data[$language['code']] = array();

                require($file);

                $this->data[$language['code']] = array_merge($this->data[$language['code']], $_);
            }
        }
    }

    public function __destruct() {
        $export = "<?php\n";
        foreach ($this->clearedData as $id => $text) {
            $export .= "\$_['$id'] = '".addslashes($text)."';\n";
        }
        //file_put_contents('simple.php', $export);
    }

    public function get($id, $onlyText = false) {
        return $onlyText ? $this->getPlainText($id) : '<span class="language-helper" id="'.$id.'">'.$this->getPlainText($id).'</span>';
    }

    public function getPlainText($id) {
        $text = !empty($this->data[$this->currentLanguage][$id]) ? $this->data[$this->currentLanguage][$id] : $id;
        $text_en = !empty($this->data['en'][$id]) ? $this->data['en'][$id] : $id;

        if ($text == $id) {
            $text = $text_en;
        }

        $this->clearedData[$id] = $text;

        return $text;
    }

    public function set($languageCode, $id, $text) {
        $this->data[$languageCode][$id] = $text;
    }

}