<?php

if (! defined('IN_SPYOGAME')) {
    exit('Hacking attempt');
}

class Setting
{
    private static  $instance = null;
    private array $datas;

    private string $modName = "paxsuperi";
    private array $allowedConf = [
        'pays',
        'uni',
        'temporisation',
        'stepperRunning',
        'currentStep',
        'lastRunning',
        'lastRunningSecurity',
    ];

    private array $allowedConfMod = [
        'pays',
        'uni',
        'temporisation',
    ];


    private function __construct()
    {
        $this->datas = array();
        // recuperation des tous les items   
        foreach ($this->allowedConf as $item) {
            $this->datas[$item] = $this->pax_mod_get_option($item);
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }




    public function resetCurrentUse()
    {
        $tabAllowedConf = array_diff($this->allowedConf, $this->allowedConfMod);

        // remise a 0 des elements non essentielles au focntionnement du mod
        foreach ($tabAllowedConf as $item) {
            $this->$item = 0;
        }
    }


    public function __get($name)
    {
        return $this->datas[$name] ?? null;
    }

    public function __set($name, $value)
    {
        if (!in_array($name, $this->allowedConf, true)) {
            throw new Exception("setting non autorisé  : $name");
        }

        $this->pax_mod_set_option($name, $value);
        $this->datas[$name] = $value;
    }


    private function pax_mod_get_option($param)
    {
        return mod_get_option($param, $this->modName);
    }
    private function pax_mod_set_option($param, $value)
    {
        return mod_set_option($param, $value,  $this->modName);
    }
}
