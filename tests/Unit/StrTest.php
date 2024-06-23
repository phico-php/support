<?php

test('toJson()', function ($expect, $str, $as_array) {

    expect(str()->toJson($str, $as_array))->toEqual($expect);

})->with([

            [(object) ['hello' => 'world'], '{"hello":"world"}', false],
            [['hello' => 'world'], '{"hello":"world"}', true],

        ]);

test('sanitise()', function ($expect, $str) {

    expect(str()->sanitise($str))->toBe($expect);

})->with([

            ['A safe string', 'A safe string'],
            ['&lt;script&gt;alert(1);&lt;/script&gt;', '<script>alert(1);</script>'],

        ]);

test('splitOnCaps()', function ($expect, $str) {

    expect(str()->splitOnCaps($str))->toBe($expect);

})->with([

            ['A Capitalised String', 'ACapitalisedString'],

        ]);
test('toCamelCase()', function ($expect, $str) {

    expect(str()->toCamelCase($str))->toBe($expect);

})->with([

            ['thisIsCamelCase', 'This is Camel case'],

        ]);
test('toKebabCase()', function ($expect, $str) {

    expect(str()->toKebabCase($str))->toBe($expect);

})->with([

            ['this-is-kebab-case', 'This is Kebab case'],

        ]);
test('toPascalCase()', function ($expect, $str) {

    expect(str()->toPascalCase($str))->toBe($expect);

})->with([

            ['ThisIsPascalCase', 'This is Pascal case'],

        ]);
test('toTrainCase()', function ($expect, $str) {

    expect(str()->toTrainCase($str))->toBe($expect);

})->with([

            ['This-Is--Train-Case', 'This is Train case'],

        ]);
