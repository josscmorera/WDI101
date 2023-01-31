const prompt = require ('prompt-sync')({sigint: true});

let num = Number(prompt('Enter a number: '));

// if (num === 10)
// {
//     console.log('Your number is equal to 10');
// }

// if (num < 10)
// {
//     console.log('Your number is less than 10');
// }


// if (num > 10)
// {
//     console.log('Your number is greater than 10');
// }

/*

=== is equal to
< is less than
> is greater than
<= is less than or equal to
>= is greater than or equal to
!= is not equal to

*/

// if (num > 10)
// {
//     console.log('Your number is greater than 10');
// }

// else if (num < 10)
// {
//     console.log('Your number is less than 10');
// }

// else if (num === 10)
// {
//     console.log('Your number is equal to 10');
// }

// else {
//     console.log('Invalid input');
// }

if (num === 10)
{
    console.log('Your number is equal to 10');
} else {
    console.log('Your number is not equal to 10');
}