# Introduction

Zsebtanár is a project aiming to provide efficient help for kids in Maths.

# Setup

1. Download an unzip the files
2. Open the website
3. Click on "Login" (password: zst)
4. Click on "Update Databse"

# Add new exercise
In order to add new exercise, you have to do two things:

1. Include class, topic, subtopic, quest and exercise data in the JSON-file
2. Create a php function to generate question and answer(s)

## STEP 1: Add exercise info to JSON-file

Exercises are stored in *public/resources/data.json*. The hierachy is the following:

1. Class
2. Topic
3. Subtopic
4. Quest
5. Exercise

Each of them *must* be provided with two attributes:
- `name`: this will appear on the website
- `label`: this will be used to define path for php file (avoid space and accents)
 
Exercises *can* have additional attributes:

- `hint`: file name stored at `public/resources/download` that can be reached by clicking on the *bulb* icon
- `youtube`: id of youtube video that can be reached by clicking on the *play* button on the website
- `level`: how many times user has to solve exercise to complete it (default: **9**)
- `status`: **OK** if exercise is finished (default: **IN PROGRESS**)

This is a sample for `data.json`:
```
{
    "classes": [
        {
            "name": "5. osztály",
            "label": 5,
            "topics": [
                {
                    "name": "Alapok",
                    "label": "basic",
                    "subtopics": [
                        {
                            "name": "Számolás",
                            "label":"counting",
                            "quests": [
                                {
                                    "name": "Számolás",
                                    "label": "counting",
                                    "exercises": [
                                        {
                                            "label": "count_apples",
                                            "name": "Számolás 1-től 20-ig",
                                            "hint": "szamok_1-20.jpg",
                                            "status": "OK"
                                        }
                                    ]
                                },
                                {
                                    "name": "Páros, páratlan",
                                    "label": "parity",
                                    "exercises": [
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
    ]
}
```

## STEP 2: Create php-function to generate exercise

1. Create file `functions_helper.php` in the `/application/helpers/exercises/class_label/topic_label/subtopic_label/` folder.
2. Define function called `exercise_label($level)`.
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
