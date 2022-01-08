<?php

namespace App\Service;

use App\Exception\HttpJsonException;
use App\Model\Question;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class QuestionExcelReader
{
    public function read(
        UploadedFile $uploadedFile
    ): Question {
        $csvReader = new Csv();

        if (false === $csvReader->canRead($uploadedFile->getPathName())) {
            throw new HttpJsonException(
                'Impossible de lire le fichier transmis.',
                400
            );
        }

        $spreadsheet = $csvReader->load($uploadedFile->getPathName());
        $active = $spreadsheet->getActiveSheet();

        $questionLabel = null;
        $listNote = [];

        foreach ($active->toArray() as $iLine => $line) {
            if (1 === count($line)) {
                if (0 === $iLine) {
                    if (is_string($line[0])) {
                        $questionLabel = ltrim(ltrim($line[0], '#'), ' ');
                    } else {
                        throw new HttpJsonException(
                            'Veuillez fournir un titre valide.',
                            400
                        );
                    }
                } else {
                    if (is_float($line[0])) {
                        $listNote[] = $line[0];
                    }
                }
            } else {
                /*
                    Erreurs si plusieurs feuilles ?
                */
            }
        }

        if (empty($listNote)) {
            throw new HttpJsonException(
                'Veuillez fournir au moins une note dans votre document.',
                400
            );
        }

        $question = new Question($questionLabel);
        $question->setValues($listNote);

        return $question;
    }
}
