<?php
namespace App\Libraries;

class CodeExecutor
{
    public function runJava($code, $testcases)
    {
        $results = [];
        $total   = count($testcases);
        $passed  = 0;

        // temp dir untuk simpan & run
        $tmpDir = sys_get_temp_dir() . '/submission_' . uniqid();
        mkdir($tmpDir);
        $javaFile = $tmpDir . '/Main.java';
        file_put_contents($javaFile, $code);

        // compile Java
        $compileOutput = [];
        exec("javac $javaFile 2>&1", $compileOutput, $compileStatus);
        if ($compileStatus !== 0) {
            return [
                'marks'    => 0,
                'feedback' => ['Compilation Error' => implode("\n", $compileOutput)],
                'details'  => []
            ];
        }

        // run setiap testcase
        foreach ($testcases as $i => $t) {
            $input    = $t['input'];
            $expected = trim($t['expected']);

            $descriptorSpec = [
                0 => ["pipe", "r"], // STDIN
                1 => ["pipe", "w"], // STDOUT
                2 => ["pipe", "w"]  // STDERR
            ];

            $process = proc_open("java -cp $tmpDir Main", $descriptorSpec, $pipes);

            if (is_resource($process)) {
                // tulis input ke STDIN
                fwrite($pipes[0], $input . "\n");
                fclose($pipes[0]);

                // baca output
                $actual = stream_get_contents($pipes[1]);
                fclose($pipes[1]);

                // baca error
                $errors = stream_get_contents($pipes[2]);
                fclose($pipes[2]);

                proc_close($process);

                $actual = trim($actual);
                $isCorrect = ($expected === $actual);

                if ($isCorrect) $passed++;

                $results[] = [
                    'testcase' => $i + 1,
                    'input'    => $input,
                    'expected' => $expected,
                    'actual'   => $actual,
                    'error'    => $errors,
                    'status'   => $isCorrect ? 'Passed' : 'Failed'
                ];
            } else {
                $results[] = [
                    'testcase' => $i + 1,
                    'input'    => $input,
                    'expected' => $expected,
                    'actual'   => '',
                    'error'    => 'Process failed to start',
                    'status'   => 'Error'
                ];
            }
        }

        // cleanup
        array_map('unlink', glob("$tmpDir/*"));
        rmdir($tmpDir);

        $marks = ($total > 0) ? round(($passed / $total) * 100, 2) : 0;

        return [
            'marks'    => $marks,
            'feedback' => ["Passed $passed of $total testcases."],
            'details'  => $results
        ];
    }
}