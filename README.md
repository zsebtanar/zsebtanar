# Introduction

Zsebtanár is a collection of interactive Maths exercises.

# Setup

You need to have a running *PHP Server* to run the website and a working internet connection for best display.

1. Download the repository.
2. Unzip the file and copy the `zsebtanar_v4` folder in your `public_html` folder (or `htdocs`, you are using *Xampp*).
3. Type in the following URLs your browser: `http://localhost/zsebtanar_v4/public/application/setup`.
4. The website can be reached through the URL: `http://localhost/zsebtanar_v4/`.

# Log in
If you want to log in the website, click on "Admin" on the right side, and type in the password (zst). After logging in, you have additional features:

1. *Update database*: run this after adding a new exercise.
2. *Clear results*: delete points earned by user.
3. *Log out*: log out the website.

Without login, only exercises with `status=OK` will be displayed. After logging in, all exercises are displayed.

# Add new exercise
In order to add new exercise, you have to do the following steps:

1. Include exercise data in the JSON-file.
2. Create a PHP class to generate exercise.
3. Update database.

## STEP 1: Add exercise info to JSON-file

Exercises are stored in *public/resources/data.json*. The hierachy is the following:

1. Class
2. Topic
3. Subtopic
4. Exercise

Each of them *must* have a `name` attribute. Exercises *must* have an additional `label` attribute, which is the name of the PHP class file (avoid space and accents). Exercises *can* have additional attributes:

- `level`: how many times user has to solve exercise to complete it (default: **9**)
- `status`: **OK** if exercise is finished (default: **IN PROGRESS**)
- `finished`: **DATE** in `YYYY-MM-DD` format (default: **(CURRENT DATE)**)

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

## STEP 2: Create PHP class to generate exercise

1. Create file `ExerciseClass.php` in the `/application/library/` folder where `ExerciseClass` is equal to `label` of the exercise in the JSON-file.
2. Define function called `Generate($level)`.
    - The input is *always* one parameter (`$level`), which is the level of exercise - this can be used to set the difficulty of the exercise.
    - Each exercise *must* be provided with the following return values:
        1. `$question`: the main body of the exercise,
        2. `$solution`: this is what the user will see if the answer is wrong.
        2. `$correct`: correct answer that will be used to compare the user's answer against.
    - Each exercise *can* be provided with additional return values:
        1. `$type`: exercise type,
        2. `$explanation`: explanation for exercise.

You can use **MathJax** to display formulas.

### Exercise types

You can create different types of exercise:

#### 1. Integer (default)

User has to send an integer as an answer. To use this you have to return an integer in the `$correct` variable.

Example:
```
/* Guess number */
class Guess_number {
    function Generate($level) {

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
}
```

#### 2. Quiz

User has to choose one answer for given options. To use this you have to return the following values:
1. `$options`: array containing options
2. `$correct`: key of correct option

Example:
```
/* Define parity of number */
class Parity {
    function Generate($level) {

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
