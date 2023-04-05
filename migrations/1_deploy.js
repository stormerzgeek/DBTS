const DirectBenefitTransfer = artifacts.require('../contracts/DirectBenefitTransfer.sol');

module.exports = function (deployer) {
    deployer.deploy(DirectBenefitTransfer);
}