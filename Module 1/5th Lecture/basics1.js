let str1 = "sunset";

console.log(str1.toUpperCase());

let str2 = "Daybreak";
console.log(str2.toLowerCase());

console.log(str1);

str1 = str1.toUpperCase(); // reassigns the value of str1 to the uppercase version of itself
console.log(str1);

let str3 = "Computer";
//          01234567
// .substring()
// grabs a portion of a string
// gives a range, the first parameter is inclusive, the second is exclusive
console.log(str3.substring(0, 4)); // Comp
console.log(str3.substring(2, 6)); // mputer

let str4 = "Today is monday";
//          0123456789
console.log(str4.substring(6,8)); 
// 1 parameter grabs from the specified index to the end of the string

console.log(str4.substring(9)); // monday
console.log(str4.substring(6)); // is monday
console.log(str4.substring(1).toUpperCase()); // oday is monday

console.log();
console.log();

let a = 5;
let b = "five";

//typeof
console.log(typeof a); // number
console.log(typeof b); // string

let c = "5";
console.log(typeof c); // string

//type casting
//string() - turns a value into a string
//number() - turns a value into a number
c = Number(c); //c is already a string, but we are converting it to a number
console.log(typeof c); // number

let d = 7;
console.log(typeof String(d)); // number
console.log(String(d)); // 7

let e = "Word!";
console.log(Number(e)); // NaN - Not a Number
console.log(typeof Number(e)); // number
console.log()