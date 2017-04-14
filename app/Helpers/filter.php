<?php

/**
 * * Checks all fields pointed in GET query according
 * to ones, allowed to choose in model.
 * @param $filterFields
 * @param $allowedFields
 * @return null
 */
function check_allowed($filterFields, $allowedFields)
{

    if (empty($filterFields)){ return null;}
    $fieldsToDelete = array_diff($filterFields, $allowedFields);

    foreach ($fieldsToDelete as $key => $val) {
        while (($i = array_search($val, $filterFields)) !== false) {
            unset($filterFields[$i]);
        }
    };

    return $filterFields;

}

/**Checks model for allowed includes with their fields in json output
 * @param $filterFields
 * @param $allowed
 * @return array|bool
 */
function check_allowed_relation_fields($filterFields, $allowed)
{

if(empty($filterFields)){return null;}
    $collection = [];
    foreach ($filterFields as $field) {

        $allowedParsed = [];
        $relation = $field['relations'];

        if ($field['relations'] == 'assets') {
            $relation = 'files';
        }

        if (array_key_exists($relation, $allowed)) {
            $allowedParsed['relations'] = $relation;
            $allowedParsed['fields'] = [];
            if (!empty($field['fields'])) {

                $allowedParsed['fields'] = check_allowed($field['fields'], $allowed[$relation]);
            }
            else{
                $allowedParsed['fields'] =[];
            }
            $collection [] = $allowedParsed;
        }


    }

    if (empty($collection)) {

        return null;
    }
    return $collection;


}