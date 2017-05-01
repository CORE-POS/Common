<?php

class BaseLogger extends PHPUnit_Framework_TestCase
{
    public function testBaseLogger()
    {
        $bl = new COREPOS\common\BaseLogger();
        $this->assertEquals(false, $bl->verboseDebugging());
        $nullfile = stristr(PHP_OS, 'WIN') ? 'nul' : '/dev/null';
        $this->assertEquals($nullfile, $bl->getLogLocation(0));
        $bl->log(0, ''); // interface method
        $bl->setRemoteSyslog('127.0.0.1');
    }

    public function testLogger()
    {
        $logger = new COREPOS\common\Logger();
        $tempfile = tempnam(sys_get_temp_dir(), 'FLT');
        $context = array('logfile' => $tempfile);
        $message = 'test logging';
        $levels = array(
            'emergency',
            'alert',
            'critical',
            'error',
            'warning',
            'notice',
            'info',
        );

        // test non-debug levels first
        foreach ($levels as $id => $level) {

            $pattern = '/^[A-Za-z]{3} \d+ \d\d:\d\d:\d\d .+ corepos\[\d+\]: \(' . $level . '\) test logging$/';

            // call emergency(), alert(), etc directly
            unlink($tempfile);
            $logger->$level($message, $context);
            $output = file_get_contents($tempfile);
            $this->assertRegExp($pattern, $output);

            // call log() with string level name
            unlink($tempfile);
            $logger->log($level, $message, $context);
            $output = file_get_contents($tempfile);
            $this->assertRegExp($pattern, $output);

            // call log() with int level ID
            unlink($tempfile);
            $logger->log($id, $message, $context);
            $output = file_get_contents($tempfile);
            $this->assertRegExp($pattern, $output);
        }

        $pattern = '/^[A-Za-z]{3} \d+ \d\d:\d\d:\d\d .+ corepos\[\d+\]: \(debug\) test logging$/';
        $frame = '/^[A-Za-z]{3} \d+ \d\d:\d\d:\d\d .+ corepos\[\d+\]: \(debug\) Frame \#\d+ .*, Line \d+, function [\w\\\\]+(::)?\w+$/';

        // test debug w/ stack trace
        unlink($tempfile);
        $context['verbose'] = true;
        $logger->debug($message, $context);
        $output = file_get_contents($tempfile);
        $lines = explode("\n", $output);
        for ($i=0; $i<count($lines); $i++) {
            if ($lines[$i] === '') {
                continue;
            }
            if ($i == 0) {
                $this->assertRegExp($pattern, $lines[$i]);
            } else {
                $this->assertRegExp($frame, $lines[$i]);
            }
        }

        // test debug again with an exception included in the context
        $e = new Exception('test exception');
        $context['exception'] = $e;
        unlink($tempfile);
        $logger->debug($message, $context);
        $output = file_get_contents($tempfile);
        $lines = explode("\n", $output);
        for ($i=0; $i<count($lines); $i++) {
            if ($lines[$i] === '') {
                continue;
            }
            if ($i == 0) {
                $this->assertRegExp($pattern, $lines[$i]);
            } else {
                $this->assertRegExp($frame, $lines[$i]);
            }
        }

        unlink($tempfile);
    }

    public function testErrorHandler()
    {
        $bl = new COREPOS\common\BaseLogger();
        $el = 'COREPOS\\common\\ErrorHandler';
        $el::setLogger($bl);

        $this->assertEquals(true, $el::errorHandler(1, 'test'));
        $el::exceptionHandler(new Exception('test'));
        $el::catchFatal();
        $el::addIgnores('asdf');
    }
}

