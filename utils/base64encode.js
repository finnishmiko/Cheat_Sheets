const encodedString = Buffer.from('test string').toString('base64');
console.log(encodedString);
const decodedString = Buffer.from(encodedString, 'base64').toString('ascii');
console.log(decodedString);