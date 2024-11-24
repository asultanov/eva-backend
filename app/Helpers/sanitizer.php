<?php

/**
 * @param array $inputs
 * @param array|null $excepts
 *
 * @return array
 */
if (!function_exists('removeHtmlTagsOfFields')) {
  function removeHtmlTagsOfFields(array $inputs, array $excepts = null)
  {
    $inputOriginal = $inputs;

    $inputs = array_except($inputs, $excepts);

    foreach ($inputs as $index => $in) {
      $inputs[$index] = strip_tags($in);
    }

    if (!empty($excepts)) {

      foreach ($excepts as $except) {
        $inputs[$except] = $inputOriginal[$except];
      }
    }

    return $inputs;
  }
}

/**
 * @param string $field
 *
 * @return string
 */
if (!function_exists('removeHtmlTagsOfField')) {
  function removeHtmlTagsOfField($field)
  {
    return htmlentities(strip_tags($field), ENT_QUOTES, 'UTF-8');
  }
}


/*
  * Usage :
  *
  *
     Remove tags of array fields in controller with excepts fields


     $inputs = removeHtmlTagsOfFields(Input::all(),[
                             '_method',
                             '_token',
                             'password'
                         ]);


     $inputs = removeHtmlTagsOfFields($request->all(),[
                                     '_method',
                                     '_token',
                                     'password'
                                ]);

     Instead of

     $input['facebook'] = strip_tags($input['facebook']);
     $input['twitter'] = strip_tags($input['twitter']);
     $input['pinterest'] = strip_tags($input['pinterest']);
     $input['linkedin'] = strip_tags($input['linkedin']);
     $input['youtube'] = strip_tags($input['youtube']);
     $input['web_site'] = strip_tags($input['web_site']);
     $input['bio_note'] = strip_tags($input['bio_note']);
     ....

     Optional excepts

     $inputs = removeHtmlTagsOfFields($request->all());

  *
  *
  * */