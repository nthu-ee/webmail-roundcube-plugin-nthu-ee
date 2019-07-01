// print NTHU EE to devtool's console
let printNthuSloganToConsole = () => console.log(
  '%c                      ',
  `
    font-family: Arial;
    font-size: 60px;
    background:
      url(https://webmail.ee.nthu.edu.tw/new/skins/elastic/images/logo-phone.png)
      left center no-repeat;
    background-size: 80%;
  `
);

module.exports = {
  printNthuSloganToConsole,
};
