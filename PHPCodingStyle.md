# Introduction #

Pour avoir un code cohérent et lisible, il faudrait respecter des conventions de codage en PHP. J'ai choisi les conventions imposées par Zend, le framework PHP le plus utilisé, parce que apparemment c'est ce qui se fait le plus couramment (vu qu'aucune convention de codage n'a l'air conseillée officiellement pour PHP).
On peut bien sûr tout changer et refaire nos propres conventions, mais autant profiter de ce qui est déjà utilisé.

Attention, ces conventions ne doivent pas faire peur et être repoussante. C'est un chemin à suivre le plus près possible pour obtenir un beau code, mais une certaines liberté est permise pour ne pas repousser le développeur...

# Démarcation du code PHP #

Le code PHP doit être délimité par les balises PHP complètes :
```
<?php

?>
```

# Chaînes #

Les chaînes doivent toujours être délimitées par des apostrophes (quotes simples)
```
$a = 'Example String';
```

Il est possible d'utiliser des quotes doubles lorsque la chaîne contient des apostrophes (requêtes SQL par exemple)
```
$sql = "SELECT `id`, `name` from `people` "
       . "WHERE `name`='Fred' OR `name`='Susan'";
```
Cette syntaxe est préférée à l'échapement des quotes car elle est plus lisible.

Les chaînes doivent être concaténées de la manière suivante, les espaces améliorent la lisibilité :
```
$company = 'Zend' . ' ' . 'Technologies';
```
Il est encouragé de couper la chaîner en plusieurs lignes pour améliorer la lisibilité.
```
$sql = "SELECT `id`, `name` FROM `people` "
     . "WHERE `name` = 'Susan' "
     . "ORDER BY `name` ASC ";
```

# Arrays #

Les arrays doivent être déclarés de la manière suivante, avec un espace après chaque virgule :
```
$sampleArray = array(1, 2, 3, 'Zend', 'Studio');
```
Les arrays déclarés sur plusieurs lignes doivent être alignés :
```
$sampleArray = array(1, 2, 3, 'Zend', 'Studio',
                     $a, $b, $c,
                     56.44, $d, 500);
```
Les valeurs peuvent aussi commencer à la ligne suivante. Le niveau d'indentation des valeurs est dans ce cas d'un niveau supérieur à la ligne d'au dessus. Toutes les lignes doivent avoir ensuite la même indentation et la fermeture de l'array se fait à la ligne au premier niveau d'indentation.
```
$sampleArray = array(
    1, 2, 3, 'Zend', 'Studio',
    $a, $b, $c,
    56.44, $d, 500,
);
```
Il est encouragé d'insérer une virgule après la dernière valeur, afin de ne pas l'oublier en cas de modification ultérieure.

Lors de l'utilisation d'arrays associatifs avec le constructeur array(), il est conseillé d'écrire la déclaration sur plusieurs lignes. Chaque ligne doit être complétée avec des espaces afin que les clés et les valeurs soient alignées :
```
$sampleArray = array('firstKey'  => 'firstValue',
                     'secondKey' => 'secondValue');
```

La première valeur de l'array peut également se trouver sur la ligne suivante. De la même manière que pour les chaînes, la valeur doit se trouver à un niveau d'indentation supérieure à la ligne précédente. Les lignes suivantes doivent être indentées de la même manière, la fermeture de l'array se fait sur une ligne séparée au premier niveau d'indentation. Les clés, le symbole "=>" et les valeurs doivent être alignés :
```
$sampleArray = array(
    'firstKey'  => 'firstValue',
    'secondKey' => 'secondValue',
);
```
Il est encouragé de mettre une virgule supplémentaire à la fin de la dernière ligne, pour éviter un oubli plus tard.

# Classes #

## Déclaration des classes ##

Les classes doivent être nommées suivant les conventions de Zend. L'accolade se trouve **en dessous du nom de la classe**. Chaque classe doit avoir un bloc de documentation conforme au standard PHPDocumentor.
Tout le code à l'intérieur d'une classe doit être indenté avec **4 espaces**.
Une classe seulement est permise par fichier PHP.
Placer du code supplémentaire dans les fichiers de classes est permis mais découragé. Dans ce cas, 2 lignes vides doivent séparer la classe du code additionnel.

### Déclaration de classe acceptable : ###
```
/**
* Documentation Block Here
*/
class SampleClass
{
    // all contents of class
    // must be indented four spaces
}
```

Les classes qui héritent d'autres classes ou qui implémentent des interfaces doivent déclarer ces dépendances sur la même ligne :
```
class SampleClass extends FooAbstract implements BarInterface
{
}
```

Si la longueur de la ligne dépasse la _longueur maximale autorisée_, effectuer un retour à la ligne avant les mots "extends" ou "implements", et indenter ces lignes d'un niveau :
```
class SampleClass
    extends FooAbstract
    implements BarInterface
{
}
```

De même, si la classe implémente plusieurs interfaces et si cela aboutit à un dépassement de la taille maximale d'une ligne, retourner à la ligne après chaque virgule séparant les interfaces, et indenter les noms pour qu'ils s'alignent :
```
class SampleClass
    implements BarInterface,
               BazInterface
{
}
```

## Attributs de classe ##
Les attributs de classes doivent être nommés selon les conventions de nommage de Zend. Toute variable déclarée dans une classe doit être listée en haut de la classe, au dessous de la déclaration de toute méthode.
Le constructeur _var_ n'est pas permis. Les attributs déclarent toujours leur visibilité avec les mots-clé _private_, _protected_, ou _public_. Donner l'accès à un attribut directement en le déclarant _public_ est permis mais découragé, en faveur des méthodes d'accès get & set.

# Fonctions et méthodes #

## Déclaration ##

Les fonctions doivent être nommées en suivant les conventions de nommage de Zend Framework. Les méthodes à l'intérieur des classes doivent toujours déclarer leur visibilité en utilisant l'un des mots-clé "private", "protected" ou "public".

Comme pour les classes, l'accolade doit toujours être une ligne en dessous du nom de la fonction. Un espace entre le nom de la fonction et la parenthèse ouvrante des paramètres n'est pas autorisé.

Les fonctions dans un contexte global sont fortement découragées. Voici un exemple d'une déclaration de fonction acceptable dans une classe :
```
/**
* Documentation Block Here
*/
class Foo
{
    /**
     * Documentation Block Here
     */
    public function bar()
    {
        // all contents of function
        // must be indented four spaces
    }
}
```

Dans le cas où la liste d'arguments dépasse la longueur maximale d'une ligne, il faut introduire un retour à la ligne. Les arguments additionnels à la fonction ou à la méthode doivent être indentés d'un niveau supplémentaire par rapport à la déclaration de la fonction/méthode. Un retour à la ligne doit dans ce cas apparaître avant la parenthèse fermante, qui doit être placée sur la même ligne que l'accolade d'ouverture de la fonction/méthode, avec un espace les séparant, et le même niveau d'indentation que la déclaration de la fonction/méthode. Voici un exemple d'une telle situation :

```
/**
* Documentation Block Here
*/
class Foo
{
    /**
     * Documentation Block Here
     */
    public function bar($arg1, $arg2, $arg3,
        $arg4, $arg5, $arg6
    ) {
        // all contents of function
        // must be indented four spaces
    }
}
```

La valeur de retour ne doit pas être entourée de parenthèses. Cela peut gêner la lisibilité, en plus de pouvoir casser le code si une méthode est changée plus tard pour retourner une référence.

```
/**
* Documentation Block Here
*/
class Foo
{
    /**
     * WRONG
     */
    public function bar()
    {
        return($this->bar);
    }
 
    /**
     * RIGHT
     */
    public function bar()
    {
        return $this->bar;
    }
}
```

## Utilisation ##

Les arguments de la fonction doivent être séparés par unique espace après la virgule de délimitation. Voici un exemple d'appel acceptable d'une fonction qui prend 3 arguments :
```
threeArguments(1, 2, 3);
```

En cas de passage d'un array à une fonction, l'appel de la fonction doit inclure le mot-clé "array" et doit être séparé en plusieurs lignes pour améliorer la lisibilité. Dans ce cas, les guidelines normales sur les arrays doivent être appliquées :
```
threeArguments(array(1, 2, 3), 2, 3);
 
threeArguments(array(1, 2, 3, 'Zend', 'Studio',
                     $a, $b, $c,
                     56.44, $d, 500), 2, 3);
 
threeArguments(array(
    1, 2, 3, 'Zend', 'Studio',
    $a, $b, $c,
    56.44, $d, 500
), 2, 3);
```

# Instructions de contrôle #

## If/Else/Elseif ##

Les instructions de contrôle basées sur une construction _if_ ou _elseif_ doivent contenir un unique espace avant la parenthèse ouvrante de la condition et un unique espace après la parenthèse fermante.

À l'intérieur de l'expression conditionnelle entre les parenthèses, les opérateurs doivent être séparés par des espaces pour la lisibilité. Les parenthèses y sont encouragées pour entourer les groupes logiques dans les grandes expressions conditionnelles.

L'accolade ouvrante est écrite sur la même ligne que l'expression conditionnelle. L'accolade fermante est toujours écrite sur sa propre ligne. Tout contenu entre les accolades doit être indenté en utilisant **4 espaces**.

```
if ($a != 2) {
    $a = 2;
}
```

Si l'expression conditionnelle dépasse la taille maximale d'une ligne et possède plusieurs clauses, il est possible d'écrire la condition sur plusieurs lignes. Dans un tel cas, il faut couper la ligne en fonction d'un opérateur logique, et décaler la ligne afin qu'elle s'aligne avec le premier caractère de la clause conditionnelle. La parenthèse fermante de la condition est dans ce cas placée sur une ligne avec l'accolade ouvrante, avec un espace les séparant, à un niveau d'indentation équivalent à l'ouverture de l'expression de contrôle.

```
if (($a == $b)
    && ($b == $c)
    || (Foo::CONST == $d)
) {
    $a = $d;
}
```

Le but de ce type de déclaration est d'éviter les problèmes à l'ajout ou à la suppression de clauses de la condition durant un révision.

Pour les expressions "if" qui utilisent "elseif" ou "else", les conventions de formatage sont les même que celle du "if". L'exemple suivant montre un formatage correct pour les expressions "if" avec une construction comprenant "elseif" ou "else" :

```
if ($a != 2) {
    $a = 2;
} else {
    $a = 7;
}
 
if ($a != 2) {
    $a = 2;
} elseif ($a == 3) {
    $a = 4;
} else {
    $a = 7;
}
 
if (($a == $b)
    && ($b == $c)
    || (Foo::CONST == $d)
) {
    $a = $d;
} elseif (($a != $b)
          || ($b != $c)
) {
    $a = $c;
} else {
    $a = $b;
}
```

PHP autorise d'écrire ces instructions sans accolades dans certains cas. Ce standard de codage n'importe pas : tous les bloc "if", "elseif" ou "else" doivent utiliser les accolades !

## Switch ##

Les instructions de contrôle écrite avec une expression "switch" doivent utiliser un unique espace avant la parenthèse ouvrante de l'expression conditionnelle et après la parenthèse fermante. Tout contenu à l'intérieur du bloc "switch" doit être indenté en utilisant 4 espaces. Le contenu compris en dessous de chaque expression "case" doit être indenté avec 4 espaces supplémentaires.

```
switch ($numPeople) {
    case 1:
        break;
 
    case 2:
        break;
 
    default:
        break;
}
```

Le bloc "default" be doit jamais être oublié dans un bloic "switch".

_Note :_ Il est parfois utile d'écrire des cas qui exécutent le cas suivant en n'incluant pas d'expression "break" ou "return". Pour distinguer ces cas des bugs, tous les blocs "case" où l'expression "break" ou "return" est omise doit contenir un commentaire indiquant que cela est intentionnel.

# Documentation #

## Format de la documentation ##

Tous les blocs de documentation ("docblocks"à doivent être compatibles avec le format phpDocumentor. Pour plus d'informations sur ce formatage, visiter http://phpdoc.org/. Tous les fichiers classe doivent contenir un bloc général au fichier au début du fichier, et un bloc spécifique à la classe au dessus de chaque classe.

## Fichiers ##

Chaque fichier contenant du code PHP doit avoir un docblock au début du fichier qui contient au minimum ces tags phpDocumentor :

```
/**
* Short description for file
*
* Long description for file (if any)...
*
* LICENSE: Some license information
*
* @category   Zend
* @package    Zend_Magic
* @subpackage Wand
* @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
* @license    http://framework.zend.com/license   BSD License
* @version    $Id:$
* @link       http://framework.zend.com/package/PackageName
* @since      File available since Release 1.5.0
*/
```

## Classes ##

Chaque classe doit avoir un docblock qui contient au minimum ces tags phpDocumentor :

```
/**
* Short description for class
*
* Long description for class (if any)...
*
* @category   Zend
* @package    Zend_Magic
* @subpackage Wand
* @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
* @license    http://framework.zend.com/license   BSD License
* @version    Release: @package_version@
* @link       http://framework.zend.com/package/PackageName
* @since      Class available since Release 1.5.0
* @deprecated Class deprecated in Release 2.0.0
*/
```

## Fonctions ##

Chaque fonction ou méthode doit avoir un docblock qui contient au minimum :
  * Une description de la fonction
  * Tous les arguments
  * Toutes les valeurs de retour possibles

Il n'est pas nécessaire d'utiliser le tag "@access" puisque le niveau d'accès est déjà connu avec le mot-clé "private", "public" ou "protected" à la déclaration de la fonction.

Si une fonction ou une méthode lance une exception, utiliser @throws pour toutes les classes d'exceptions connues :

```
@throws exceptionclass [description]
```

# Page contenant les conventions complètes #

http://framework.zend.com/manual/en/coding-standard.coding-style.html