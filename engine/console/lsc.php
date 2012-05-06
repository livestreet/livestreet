<?php

abstract class LSC {

    /*
     * Запускаем работу консоли
     */
    static function Start() {
        $aArgs = $_SERVER['argv'];

        // Если не передана команда выводим помощь
        if(count($aArgs)==1) {
            echo self::getHelp()."\n";
            return ;
        }

        $sCommandClassName = ucwords($aArgs[1]);
        $sCommandClassPath = dirname(__FILE__).'/commands/'.$sCommandClassName.'.class.php';

        // Существует ли такой класс, а следовательно и команда
        if(file_exists($sCommandClassPath)) {
            // Подключаем класс команды
            require_once $sCommandClassPath;
            $oCommand = new $sCommandClassName();
            $oCommand->run($aArgs);
        } else {
            die("Command not isset\n");
        }
    }

    /*
     * Отдаем управление вызванной команде
     */
    public function run($aArgs) {
        // Если не передана подкоманда или передана подкоманда help выводим помощь
        if(!isset($aArgs[2]) or $aArgs[2]=='help') {
            echo $this->getHelp()."\n";
            return ;
        }

        $sMethodName = 'action'.ucwords($aArgs[2]);

        // Оставляем в массиве только параметры для подкоманды
        array_shift($aArgs);
        array_shift($aArgs);
        array_shift($aArgs);

        $this->$sMethodName($aArgs);
    }

    /*
     * Создает массив файлов используемый при копировании
     */
    public function buildFileList($sSourceDir, $sTargetDir, $sBaseDir='')
    {
        $aList=array();
        $handle=opendir($sSourceDir);
        while(($sFile=readdir($handle))!==false)
        {
            if($sFile==='.' || $sFile==='..' || $sFile==='.svn' ||$sFile==='.gitignore')
                continue;

            $sSourcePath=$sSourceDir.DIRECTORY_SEPARATOR.$sFile;
            $sTargetPath=$sTargetDir.DIRECTORY_SEPARATOR.$sFile;

            $sName=($sBaseDir==='')? $sFile : $sBaseDir.'/'.$sFile;

            // Строим массив с ключем в виде имени файла или папки, пути к исходнику и пути назначения
            $aList[$sName]=array(
                'source'=>$sSourcePath,
                'target'=>$sTargetPath
            );

            // Если директория то рекурсивно получаем массив его содержимого и объединяем с главным
            if(is_dir($sSourcePath)) {
                $aList=array_merge($aList,$this->buildFileList($sSourcePath,$sTargetPath,$sName));
            }
        }
        closedir($handle);
        return $aList;
    }

    /*
     * Копирование файлов
     */
    public function copyFiles($fileList)
    {
        $overwriteAll=false;
        foreach($fileList as $name=>$file)
        {
            $source=strtr($file['source'],'/\\',DIRECTORY_SEPARATOR);
            $target=strtr($file['target'],'/\\',DIRECTORY_SEPARATOR);

            $callback=isset($file['callback']) ? $file['callback'] : null;
            $params=isset($file['params']) ? $file['params'] : null;

            if(is_dir($source))
            {
                // Проверяем существует ли директория или досоздаем папки
                $this->ensureDirectory($target);
                continue;
            }

            // Если существует коллбэк то вызываем его
            if($callback!==null)
                $content=call_user_func($callback,$source,$params);
            // Либо отдаем содержимое исходника без изменений
            else
                $content=file_get_contents($source);

            // Если файл в папке назначения уже существует
            if(is_file($target))
            {
                // Если содержимое старого и нового файла совпадают
                if($content===file_get_contents($target))
                {
                    echo "  unchanged $name\n";
                    continue;
                }

                // Если мы выбрали перезапись в ветке false
                if($overwriteAll)
                    echo "  overwrite $name\n";
                else
                {
                    echo "      exist $name\n";
                    echo "            ...overwrite? [Yes|No|All|Quit] ";

                    // Спрашиваем у пользователя как поступить
                    $answer=trim(fgets(STDIN));
                    if(!strncasecmp($answer,'q',1))
                        return;
                    else if(!strncasecmp($answer,'y',1))
                        echo "  overwrite $name\n";
                    else if(!strncasecmp($answer,'a',1))
                    {
                        echo "  overwrite $name\n";
                        $overwriteAll=true;
                    }
                    else
                    {
                        echo "       skip $name\n";
                        continue;
                    }
                }
            }
            // Если файла еще не существует
            else
            {
                // Досоздаем папки в случае отсутствия
                $this->ensureDirectory(dirname($target));
                echo "   generate $name\n";
            }

            // Создаем файл и записываем в него содержимое
            file_put_contents($target,$content);
        }
    }

    /**
     * Создает родительские папки если они не существуют
     * @param string $directory
     */
    public function ensureDirectory($directory)
    {
        if(!is_dir($directory))
        {
            $this->ensureDirectory(dirname($directory));
            echo "      mkdir ".strtr($directory,'\\','/')."\n";
            mkdir($directory);
        }
    }

    /*
     * Выводит помощь и список возможных команд
     */
    public function getHelp() {
        $aList=array();
        $handle=opendir(dirname(__FILE__).'/commands/');
        while(($file=readdir($handle))!==false)
        {
            if($file==='.' || $file==='..')
                continue;
            if(is_file(dirname(__FILE__).'/commands/'.$file))
                $aList[]=strtolower(preg_replace("/^(.*)\.(.*)\.(.*)/i","$1",$file));
        }
        closedir($handle);

        echo "USAGE\n
  ls ";

        foreach($aList as $iKey=>$sName) {
            if($iKey>0)
                echo "     ";
            echo $sName."\n";
        }

    }
}
