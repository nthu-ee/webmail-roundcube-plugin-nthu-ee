const funcs = require('./js/functions');

const debug = false;

const $ = global.$;
const rcmail = global.rcmail;
const rcube_parse_query = global.rcube_parse_query;

let comm_path = rcube_parse_query(rcmail.env.comm_path);
let is_framed = Boolean(parseInt(typeof comm_path._framed !== 'undefined' ? comm_path._framed : 0));

if (!is_framed) {
  funcs.printNthuSloganToConsole();
}
