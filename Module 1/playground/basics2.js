let num1 = 6;

num1 = num1+1;

console.log(num1);

num1++; // increment by 1

console.log(num1);

num1 += 1; // shorthand for num1 = num1 + 1; does addition adn reassigment

console.log(num1);

// -=, *=, /=, %=

console.log();

// % - modulus
console.log("modulus");
console.log(10%3); // 1
console.log(10%4); // 0
console.log(10%5); // 0

console.log("exponents/square roots");
let num2 = 2
// number to raise ** power to rise

console.log(num2 ** 4); // 4
console.log(Math.pow(num2,3)); // 4
console.log(Math.sqrt(100)); // 4

console.log();

let p = 5;
let q = 8;
let temp = 0;

temp = p
p = q
q = temp

console.log(p);
console.log(q);

let srt1 = "String 1";
let srt2 = "String 2";
let strTemp = "";

strTemp = srt1;
srt1 = srt2;
srt2 = strTemp;

console.log(srt1);
console.log(srt2);


console.log();

let numx = 1;
num2 = numx + 1;
num3 = numx + 2;
num4 = numx + 3;
num5 = numx + 4;
num6 = numx + 5;
num7 = numx + 6;
random = Math.floor(Math.random() * (num7 - numx) + numx);
console.log(random);