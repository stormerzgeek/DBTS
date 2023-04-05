// SPDX-License-Identifier: MIT
pragma solidity ^0.8.18;

contract DirectBenefitTransfer {
    struct Farmer {
        uint id;
        string name;
        uint landArea;
        string aadharNumber;
        string bankAccount;
        uint subsidyAmount;
        string schemeName;
        bool isRegistered;
    }

    mapping(address => Farmer) public farmers;
    uint public farmerCount;
    address public government;
    uint public totalSubsidyAmount;

    constructor() {
        government = msg.sender;
        farmerCount = 0;
    }

    modifier onlyGovernment() {
        require(
            msg.sender == government,
            "Only government can access this function"
        );
        _;
    }

    event FarmerRegistered(
        address _farmer,
        uint id,
        string _name,
        uint _landArea,
        string _aadharNumber,
        string _schemeName,
        string _bankAccount
    );
    event SchemeApproved(
        address _farmer,
        uint _subsidyAmount,
        string _schemeName,
        bool _isSchemeApproved
    );
    event GetDetails(
        address _farmer,
        uint _id,
        string _name,
        uint _landArea,
        string _aadharNumber,
        string _bankAccount,
        uint _subsidyAmount,
        string _schemeName,
        bool _isRegistered
    );

    function updateSubsidyAmount(
        address _farmer,
        uint _subsidyAmount,
        string memory _schemeName,
        bool _isSchemeApproved
    ) public onlyGovernment {
        require(farmers[_farmer].isRegistered, "Farmer is not registered");
        farmers[_farmer].subsidyAmount = _subsidyAmount;
        if (_isSchemeApproved) {
            farmers[_farmer].schemeName = string.concat(
                "PM-KISAN ",
                _schemeName,
                " is aprroved"
            );
            totalSubsidyAmount += _subsidyAmount;
            emit SchemeApproved(
                msg.sender,
                _subsidyAmount,
                _schemeName,
                _isSchemeApproved
            );
        } else {
            farmers[_farmer].schemeName = string.concat(
                _schemeName,
                " not approved"
            );
        }
    }

    function registerFarmer(
        string memory _name,
        uint _landArea,
        string memory _aadharNumber,
        string memory _bankAccount,
        string memory _schemeName
    ) public {
        require(
            !farmers[msg.sender].isRegistered,
            "Farmer is already registered"
        );
        farmerCount++;
        farmers[msg.sender] = Farmer(
            farmerCount,
            _name,
            _landArea,
            _aadharNumber,
            _bankAccount,
            0,
            _schemeName,
            true
        );
        emit FarmerRegistered(
            msg.sender,
            farmerCount,
            _name,
            _landArea,
            _aadharNumber,
            _schemeName,
            _bankAccount
        );
    }

    function getFarmerDetails(
        address _farmer
    )
        public
        returns (
            uint,
            string memory,
            uint,
            string memory,
            string memory,
            uint,
            string memory,
            bool
        )
    {
        require(farmers[_farmer].isRegistered, "Farmer is not registered");
        Farmer memory farmer = farmers[_farmer];
        emit GetDetails(
            msg.sender,
            farmer.id,
            farmer.name,
            farmer.landArea,
            farmer.aadharNumber,
            farmer.bankAccount,
            farmer.subsidyAmount,
            farmer.schemeName,
            farmer.isRegistered
        );
        return (
            farmer.id,
            farmer.name,
            farmer.landArea,
            farmer.aadharNumber,
            farmer.bankAccount,
            farmer.subsidyAmount,
            farmer.schemeName,
            farmer.isRegistered
        );
    }
}
