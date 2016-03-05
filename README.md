# Introduction

Zsebtanár is a project aiming to provide efficient help for kids in Maths.

# Setup

First, download an unzip the files.

Type in the following URLs your browser:

`http://localhost/zsebtanar_v4/public/database/setup`

If you want to log in the website, click on "login", and type in the password (zst).

After logging in, you have additional features:

1. *Update database*: run this after adding a new exercise.
2. *Clear results*: delete points.
3. *Log out*

# Add new exercise
In order to add new exercise, you have to do two things:

1. Include class, topic, subtopic, quest and exercise data in the JSON-file
2. Create a php class to generate question and answer(s)

## STEP 1: Add exercise info to JSON-file

Exercises are stored in *public/resources/data.json*. The hierachy is the following:

1. Class
2. Topic
3. Subtopic
4. Exercise

Each of them *must* be provided with the following attributes:
- `name`: this will appear on the website

Exercises *must* have an additional attribute:
- `label`: this will be used to define path for php file (avoid space and accents)

Exercises *can* have additional attributes:

- `level`: how many times user has to solve exercise to complete it (default: **9**)
- `status`: **OK** if exercise is finished (default: **IN PROGRESS**)

This is a sample for `data.json`:
```
{
    "classes": [
        {
            "name": "5. osztály",
            "topics": [
                {
                    "name": "Alapok",
                    "subtopics": [
                        {
                            "name": "Számolás",
                            "exercises": [
                                {
                                    "label": "count_apples",
                                    "name": "Számolás 1-től 20-ig",
                                    "status": "OK"
                                },
                                {
                                    "label": "parity",
                                    "name": "Páros vagy páratlan?",
                                    "level": "4"
                                }
                            ]
                        }
                    ]
                }
            ]
        }
    ]
}
```

## STEP 2: Create php-function to generate exercise

1. Create file `ExerciseClass.php` in the `/application/library/` folder where `ExerciseClass` is equal to `label`.
2. Define function called `Generate($level)`.
    - The input is *always* one parameter (`$level`), which is the level of exercise - this can help to set the difficulty.
    - Each exercise *must* be provided with the following return values:
        1. `$question`: the main body of the exercise
        2. `$solution`: this is what the user will see if the answer is wrong 

You can use **MathJax** to display formulas.

### Exercise types

You can create different types of exercise:

#### 1. Integer (default)

User has to send an integer as an answer. To use this you have to return the following values:
1. `$correct`: correct answer (integer)

Example:
```
/* Count apples from 1 to 20 */
function count_apples($level) {

    $num = rand($level, 3*$level);

    $question = 'How many is $2\cdot'.$num.'$?';
    $correct = 2*$num;
    $solution = '$'.$correct.'$'; // use MathJax for better display

    return array(
        'question'      => $question,
        'correct'       => $correct,
        'solution'      => $solution
    );
}
```

#### 2. Quiz

User has to choose one answer for given options. To use this you have to return the following values:
1. `$options`: array containing options
2. `$correct`: key of correct answer

Example:
```
/* Define parity of number */
function parity($level) {

    $num = rand($level, 3*$level);

    $question = 'Is the following even or odd?$$'.$num.'$$';

    $options = array('even', 'odd');
    $index = $num%2;
    $solution = $options[$index];

    shuffle($options); // shuffle options
    $correct = array_search($solution, $options); // search key of correct answer

    return array(
        'question'  => $question,
        'options'   => $options,
        'correct'   => $correct,
        'solution'  => $solution
    );
}
```

#### 3. Multi
...
#### 4. Fraction
...
#### 5. Division
...
#### 6. Text
...

### Additional features
#### 1. Using pictures
- **Loading pictures**
- **Generating pictures**
...

#### 2. Built in functions
- **Language functions**
- **Math functions**

#### 3. Explanation
...

# Contact

- Website: http://www.zsebtanar.hu
- Email: zsebtanar@gmail.com
