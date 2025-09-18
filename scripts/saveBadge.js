const fs = require("fs");
const {makeBadge} = require("badge-maker");

/**
 * @typedef { import("badge-maker").Format } ShieldsOptions
 */

/**
 * @param {string} filename
 * @param {ShieldsOptions} options
 */
function saveBadge(filename, options) {
    fs.writeFileSync(filename, makeBadge(options), {encoding: "utf8"});
    console.log(`Saved ${filename} badge.`);
}

module.exports = {
    saveBadge,
};
