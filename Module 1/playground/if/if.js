const prompt = require('prompt-sync')({sigint: true});

// let myPrompt = prompt('Enter a string: ');

// console.log(myPrompt);

let num1 =  Number(prompt('Enter your first number: '));
// prompt by itself will always create a string, so if you want to use it as a number, you need to convert it Number() cast

let num2 = Number(prompt('Enter your second number: '));


let op = prompt('Enter an operator (+,-,*,/): ');


// console.log(num1);
// console.log(num2);
// console.log(op);

//if statement
// if statements are used to evaluate conditinal logic in our programs and control the flow of how they run
// if the condition is true, the code block will run whats inside the curly braces
// if the condition is false, the code block will not run
/* 

if (condition === true)

{
    do something!!
}   
*/

//if true, I will do something

if (op === '+')
{
    console.log(num1 + num2)
}
else if (op === '-')
{
    console.log(num1 - num2)
}
else if (op === '*')
{
    console.log(num1 * num2)
}
else if (op === '/')
{
    console.log(num1 / num2)
} else {
    console.log('Invalid operator');
}