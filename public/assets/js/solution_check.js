/*!
 * Solution check JS function
 * Licensed under CC BY-NC-SA 4.0 license.
 *
 * @author Viktor Szabó
 * @link http://www.zsebtanar.hu
 */

// Check answer
function checkAnswer(id, correct_JSON, solution, type) {

	if (type == 'int') { // <-- INTEGER type solution

		// Get answer
		var x = document.forms["exercise"+id]["answer"].value;

		// Missing answer
		if (x == null || x == "") {
			document.getElementById("error"+id).innerHTML = "Hiányzik a válasz!";
			return;
		}  

		// Check spaces
		checkSpaces(x);

		// Compare Solution
		var answer = parseInt(x);
		var correct = JSON.parse(correct_JSON);
		var result = compareSolution(answer, correct, solution, id);
		if (answer == correct) {
			printAnswerCorrect(id);
		} else {
			printAnswerWrong(solution, id);
		}

		// Disable buttons
		document.forms["exercise"+id]["answer"].disabled = true;

	} else if (type == 'quotient') {

		// Get answer
		var quotient = document.forms["exercise"+id]["quotient"].value;
		var remnant = document.forms["exercise"+id]["remnant"].value;

		// Missing answer
		if (quotient == null || quotient == "") {quotient = 0;}
		if (remnant == null || remnant == "") {remnant = 0;}

		// Check spaces
		checkSpaces(quotient);
		checkSpaces(remnant);

		// Compare Solution
		var correct = JSON.parse(correct_JSON);
        if (quotient == correct[0] && remnant == correct[1]) {
	        printAnswerCorrect(id);
	    } else {
	    	printAnswerWrong(solution, id);
	    }

		// Disable buttons
		document.forms["exercise"+id]["quotient"].disabled = true;
		document.forms["exercise"+id]["remnant"].disabled = true;

	}

	// Disable buttons
	// var radios = document.forms["exercise"+id]["answer"];

	// if (Array.isArray(radios)) {
	// 	for (var i=0, iLen=radios.length; i<iLen; i++) {
	// 		radios[i].disabled = true;
	// 	}
	// } else {
	// 	radios.disabled = true;
	// }

	return;
}

// Compare solution
function compareSolution(answer, correct, solution, id) {

	if (answer === correct) {
		document.getElementById("error"+id).innerHTML = "";
		document.getElementById("button"+id).innerHTML = "<button class=\"btn btn-success\"><span class=\"glyphicon glyphicon-ok\"></span></button>&nbsp;&nbsp;<button class=\"btn btn-primary\" onclick=\"reloadPage()\"><span class=\"glyphicon glyphicon-refresh\"></span></button>";
	} else {
	}

	return;
} 

// Check spaces
function checkSpaces(x) {
	x_nospaces = x.toString().replace(/\s/g,'');
	if (x != x_nospaces) {
		document.getElementById("error"+id).innerHTML = "Ne használj szóközöket!";
		return;
	}
}

// Print message for correct answer
function printAnswerCorrect(id) {

	document.getElementById("error"+id).innerHTML = "";
	document.getElementById("button"+id).innerHTML = "<button class=\"btn btn-success\"><span class=\"glyphicon glyphicon-ok\"></span></button>&nbsp;&nbsp;<button class=\"btn btn-primary\" onclick=\"reloadPage()\"><span class=\"glyphicon glyphicon-refresh\"></span></button>";

	return;
} 

// Print message for wrong answer
function printAnswerWrong(solution, id) {

	document.getElementById("error"+id).innerHTML = "A helyes megoldás: " + solution;
	MathJax.Hub.Queue(["Typeset",MathJax.Hub,"error"+id]);
	document.getElementById("button"+id).innerHTML = "<button class=\"btn btn-danger\"><span class=\"glyphicon glyphicon-remove\"></span></button>&nbsp;&nbsp;<button class=\"btn btn-primary\" onclick=\"reloadPage()\"><span class=\"glyphicon glyphicon-refresh\"></span></button>";

	return;
} 