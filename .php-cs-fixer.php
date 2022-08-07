<?php

$header = <<<HEADER
This file is part of PHP CS Fixer.

(c) vinhson <15227736751@qq.com>

This source file is subject to the MIT license that is bundled
with this source code in the file LICENSE.
HEADER;

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/app',
        __DIR__ . '/config',
        __DIR__ . '/database',
        __DIR__ . '/routes',
        __DIR__ . '/tests',
    ])
    ->exclude([
        __DIR__ . '/vendor',
    ])
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        'header_comment' => ['header' => $header],
        'array_syntax' => ['syntax' => 'short'],
        'no_useless_else' => true, // 删除没有使用的else节点
        'not_operator_with_successor_space' => true, // 逻辑非运算符 (!) 应该有一个尾随空格。
        'phpdoc_scalar' => true,
        'unary_operator_spaces' => true,
        'binary_operator_spaces' => true,
        'blank_line_before_statement' => [
            'statements' => ['break', 'continue', 'declare', 'return', 'throw', 'try'],
        ],
        'phpdoc_single_line_var_spacing' => true,
        'phpdoc_var_without_name' => true,
        'class_attributes_separation' => [
            'elements' => [
                'method' => 'one',
            ],
        ],
        'method_argument_space' => [
            'on_multiline' => 'ensure_fully_multiline',
            'keep_multiple_spaces_after_comma' => true,
        ],
        'single_trait_insert_per_statement' => true,

        // add
        'trim_array_spaces' => true,
        'combine_consecutive_unsets' => true, // 当多个 unset 使用的时候，合并处理

        // operator
        'concat_space' => ['spacing' => 'one'], // .拼接必须有空格分割
        'new_with_braces' => true, // 使用 new 关键字创建的所有实例都必须后跟大括号。
        'no_space_around_double_colon' => true, // 双冒号周围不能有空格（也称为范围解析运算符或 Paamayim Nekudotayim）。
        'object_operator_without_whitespace' => true, // 对象运算符 -> 和 ?-> 之前或之后不应有空格。
        'ternary_operator_spaces' => true, // 标准化三元运算符周围的空格。
//        'ternary_to_elvis_operator' => true, // 尽可能使用 Elvis 运算符 ?: 。
        'ternary_to_null_coalescing' => true, // 使用空合并运算符 ??在可能的情况。需要 PHP >= 7.0。

        // phpdoc
        'align_multiline_comment' => true, // 多行 DocComments 的每一行必须有一个星号 [PSR-5] 并且必须与第一行对齐
        'no_blank_lines_after_phpdoc' => true, // docblock 和文档元素之间不应该有空行。

        // return
        'no_useless_return' => true, // 删除没有使用的return语句
        'return_assignment' => true, // 函数或方法不应分配和直接返回局部、动态和直接引用的变量。

        // namespace_notation
        'blank_line_after_namespace' => true, // 命名空间声明后必须有一个空行。
        'no_leading_namespace_whitespace' => true, // 命名空间声明行不应包含前导空格。
        'single_blank_line_before_namespace' => true, // 在命名空间声明之前应该正好有一个空行。
//        'no_blank_lines_before_namespace' => true, // 命名空间声明前不应有空行。

        // import
        'fully_qualified_strict_types' => true, // 将导入的 FQCN 参数和函数参数中的返回类型转换为短版本。
        'global_namespace_import' => [ // value true、array 导入或完全限定的全局类函数常量。
            'import_classes' => true,
            'import_constants' => true,
            'import_functions' => true
        ],
        'group_import' => true, // 该设置为 true 时，single_import_per_statement 必须设置为 false, 否则两个设置会冲突
        'single_import_per_statement' => false, // 默认为 true
        'no_unused_imports' => true, //删除没用到的use
        'ordered_imports' => [ // 排序使用语句。
            'sort_algorithm' => 'length',
            'imports_order' => ['const', 'class', 'function']
        ],
        'single_line_after_imports' => true, // 每个命名空间 use 必须在自己的行中，并且在 use 语句块之后必须有一个空行。

        /**
         * @see https://github.com/w7corp/easywechat/blob/5.x/.php_cs
         */
        'blank_line_after_opening_tag' => true,
        'compact_nullable_typehint' => true,
        'declare_equal_normalize' => true,
        'lowercase_cast' => true,
        'lowercase_static_reference' => true,
        'no_blank_lines_after_class_opening' => true,
        'no_leading_import_slash' => true,
        'no_whitespace_in_blank_line' => true,
        'ordered_class_elements' => [
            'order' => [
                'use_trait',
            ],
        ],
//        'ordered_imports' => [
//            'imports_order' => [
//                'class',
//                'function',
//                'const',
//            ],
//            'sort_algorithm' => 'none',
//        ],
        'return_type_declaration' => true,
        'short_scalar_cast' => true,
        'visibility_required' => [
            'elements' => [
                'const',
                'method',
                'property',
            ],
        ],
    ])
    ->setFinder($finder);
