/* eslint-disable @typescript-eslint/no-require-imports */
const {spawnSync} = require('child_process');
const {saveBadge} = require("./saveBadge");

/**
 * @typedef AuditReport
 * @type {object}
 * @property {number} auditReportVersion
 * @property {Record<string,AuditReportVulnerability>} vulnerabilities
 * @property {AuditReportMetaData} metadata
 */

/**
 * @typedef AuditReportVulnerability
 * @type {object}
 * @property {string} name
 * @property {string} severity
 * @property {boolean} isDirect
 * @property {array<string|AuditReportVulnerabilityVia>} via
 * @property {array<string>} effects
 * @property {string} range
 * @property {array<string>} nodes
 * @property {AuditReportVulnerabilityFixAvailable} fixAvailable
 */

/**
 * @typedef AuditReportVulnerabilityVia
 * @type {object}
 * @property {number} source
 * @property {string} name
 * @property {string} dependency
 * @property {string} title
 * @property {string} url
 * @property {string} severity
 * @property {string} range
 */

/**
 * @typedef AuditReportVulnerabilityFixAvailable
 * @type {object}
 * @property {string} name
 * @property {string} version
 * @property {boolean} isSemVerMajor
 */

/**
 * @typedef AuditReportMetaData
 * @type {object}
 * @property {AuditReportMetaDataVulnerabilities} vulnerabilities
 * @property {AuditReportMetaDataDependencies} dependencies
 */

/**
 * @typedef AuditReportMetaDataVulnerabilities
 * @type {object}
 * @property {number} info
 * @property {number} low
 * @property {number} moderate
 * @property {number} high
 * @property {number} critical
 * @property {number} total
 */

/**
 * @typedef AuditReportMetaDataDependencies
 * @type {object}
 * @property {number} prod
 * @property {number} dev
 * @property {number} optional
 * @property {number} peer
 * @property {number} peerOptional
 * @property {number} total
 */


/**
 * @returns {AuditReport}
 */
function getAuditReport()
{
    const result = spawnSync('npm', ['audit', '--json'], {encoding: 'utf8'}).stdout;
    return JSON.parse(result);
}

/**
 * @param {AuditReport} audit
 * @return {string}
 */
function getBadgeColor(audit)
{
    const total = audit.metadata.vulnerabilities.total;
    const highOrCritical = audit.metadata.vulnerabilities.high + audit.metadata.vulnerabilities.critical;

    if (total === 0) {
        return 'success';
    } else if (highOrCritical > 0) {
        return 'critical';
    } else {
        return 'important';
    }
}

/**
 * @type {AuditReport}
 */
const audit = getAuditReport();
saveBadge(
    'audit.badge.svg',
    {
        label: 'NPM Vulnerabilities',
        message: audit.metadata.vulnerabilities.total.toString(),
        color: getBadgeColor(audit),
    }
);
