<?php

function qi_process_csv($filepath){
    $questionFile = fopen($filepath, 'r');
    if($questionFile){
        $keys = fgetcsv($questionFile); //first line is the header 

        $row = fgetcsv($questionFile);
        while($row !== false){
            
            
            $title = $row[0];
            $options = [];
            $index = 1;
            foreach($row as $str){
                if($title === $str) continue;
                $index++;

                $options[] = preg_replace('/[^0-9a-zA-Z]/', '', $str);
                if(str_contains($str, ']')) break;
            }

            $correctAnswer = [];
            for($index; $index<count($row);$index++){
                $correctAnswer[] = preg_replace('/[^0-9]/', '', $row[$index]);
            }
        
            $newQuestionPost = wp_insert_post([
                'post_title' => $title,
                'post_type' => 'question',
                'post_status' => 'publish'
            ]);

            if(!is_wp_error($newQuestionPost)){
                update_field('answers',$options,$newQuestionPost);
                update_field('correct_answer',$correctAnswer,$newQuestionPost);
            }

            $row = fgetcsv($questionFile);
        }

        fclose($questionFile);
        echo "<div class='notice notice-success'><p>Import Complete!</p></div>";
    }
    else{
        echo "<div class='notice notice-error'><p>Cannot read the CSV file.</p></div>";
    }
}