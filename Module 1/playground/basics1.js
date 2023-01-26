console.log('Javascript Basics');

// This is a single line comment!

/* 
This 
is 
a 
multi-line 
comment */

// Variables
let a = 5;

// Variable Declaration - creating a variable with a keyword ('let')
// always make sure to use variables AFTER declaring them
// using them beforehand will result in an error
// command + / to comment out a line

console.log(a);
console.log("a");

// let bigNumber = 500000;
// console.log(bigNumber);

let b = 7;
console.log(a+b); //Addition
console.log(a-b); //Subtraction
console.log(a*b); //Multiplication
console.log(a/b); //Division
console.log(10/2); //Division

let c = a + b;

console.log(c);

let charName = "Cloud"; //String
console.log(charName);

// String Concatenation - adding strings together

console.log("This character's name is " + charName + ".");

// String Interpolation - using backticks and ${} to add variables to strings
console.log(`This character's name is ${charName}.`);

let charLastName = "Strife";

let charfullName = charName + " " + charLastName;

console.log(charfullName);

let n = 10;
console.log(n);

// Variable Reassignment - changing the value of a variable

n = 15;

// changes n from being equal to 10, to being equal to 15
console.log(n);

let x = 9;
let y = 2;

console.log(x);
console.log(y);

console.log("x");

let z = x*y;
console.log(z);

let color = "blue";
console.log("my favorite color is " + color + ".");

console.log(); // empty console.log() to add a space or line break

console.log(`my favorite color is ${color}.`);

// variable duplication using the same variable
z = z*2;

console.log(z);

// Escaping Characters - using a backslash to escape a character
console.log("this person's said \"Hello\".");