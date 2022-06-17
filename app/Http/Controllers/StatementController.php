<?php

namespace App\Http\Controllers;

use App\Models\Statement;
use Illuminate\Routing\Controller as BaseController;

class StatementController extends BaseController
{
    public function saveStatements($construct_id, $questionnaire_id, $statements_info) {

        if (empty($statements_info)) {
            return false;
        }
        $position = 1;
        foreach ($statements_info as $index=>$statement_info) {

            if (isset($statement_info['id'])) {
                // update existing statement
                $statement = Statement::find($statement_info['id']);
                if (empty($statement_info['text'])) {
                    $statement->delete();
                }
                if (isset($statement_info['text'])) {
                    $statement->text = $statement_info['text'];
                    $statement->position = $position++;
                    $statement->save();
                }
            } else {
                // create new statement
                if (isset($statement_info['text'])) {
                    $statement = Statement::create([
                        'text' => $statement_info['text'],
                        'construct_id' => $construct_id,
                        'questionnaire_id' => $questionnaire_id,
                        'position' => $position++,
                    ]);
                }
            }

        }
    }
}
