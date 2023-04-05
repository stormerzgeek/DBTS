<?php include ( "db.php" ); ?>
<?php 
ob_start();
session_start();
if (!isset($_SESSION['user_login'])) {
	$user = "";
    $uname_db="";
    $wallet_user="";
}
else {
	$user = $_SESSION['user_login'];
	$result = mysqli_query($con,"SELECT * FROM user WHERE id='$user'");
		$get_user_email = mysqli_fetch_assoc($result);
			$uname_db = $get_user_email['firstName'];
            $wallet_user = $get_user_email['Wallet_address'];
}   
?>
<!DOCTYPE html>
<html>

<head>
    <title>Direct Benefit Transfer System</title>
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/web3/1.2.7-rc.0/web3.min.js"></script>

    <?php
            $con = mysqli_connect("localhost","root","") or die("Error ".mysqli_error($con));
            mysqli_select_db($con,'blockchain') or die("cannot select DB"); ?>
</head>

<body>
    <?php include ( "mainheader.php" ); ?>
    <div class="names" style="text-align: center;">
        <p class="titles">REGISTER FOR FARMER SCHEMES</p><br><br>
        <label for="name">Name:</label>
        <input type="text" id="name"><br><br>
        <label for="landarea">Land Area(in acres):</label>
        <input type="text" id="landarea"><br><br>
        <label for="aadharno">Aadhar Number:</label>
        <input type="text" id="aadharno"><br><br>
        <label for="bankacc">Bank Account Number:</label>
        <input type="text" id="bankacc"><br><br>
        <label for="schemename">Scheme Name:</label>
        <input type="text" id="schemename"><br><br>
        <button onclick="registerfarmer()" id="registerfarmer" class="uisignupbutton">Register</button>
        <button onclick="readfarmer()" class="uisignupbutton">Get Scheme Approval Details</button> <br>
        <p class="titles" id="dataArea"></p>
    </div>
    

    <script>
        let account;
        const init = async () => {
            if (window.ethereum !== "undefined") {
                const accounts = await ethereum.request({ method: "eth_requestAccounts" });
                account = accounts[0];
            }
            const ABI = [
                {
                "inputs": [],
                "stateMutability": "nonpayable",
                "type": "constructor"
                },
                {
                "anonymous": false,
                "inputs": [
                    {
                    "indexed": false,
                    "internalType": "address",
                    "name": "_farmer",
                    "type": "address"
                    },
                    {
                    "indexed": false,
                    "internalType": "uint256",
                    "name": "id",
                    "type": "uint256"
                    },
                    {
                    "indexed": false,
                    "internalType": "string",
                    "name": "_name",
                    "type": "string"
                    },
                    {
                    "indexed": false,
                    "internalType": "uint256",
                    "name": "_landArea",
                    "type": "uint256"
                    },
                    {
                    "indexed": false,
                    "internalType": "string",
                    "name": "_aadharNumber",
                    "type": "string"
                    },
                    {
                    "indexed": false,
                    "internalType": "string",
                    "name": "_schemeName",
                    "type": "string"
                    },
                    {
                    "indexed": false,
                    "internalType": "string",
                    "name": "_bankAccount",
                    "type": "string"
                    }
                ],
                "name": "FarmerRegistered",
                "type": "event"
                },
                {
                "anonymous": false,
                "inputs": [
                    {
                    "indexed": false,
                    "internalType": "address",
                    "name": "_farmer",
                    "type": "address"
                    },
                    {
                    "indexed": false,
                    "internalType": "uint256",
                    "name": "_id",
                    "type": "uint256"
                    },
                    {
                    "indexed": false,
                    "internalType": "string",
                    "name": "_name",
                    "type": "string"
                    },
                    {
                    "indexed": false,
                    "internalType": "uint256",
                    "name": "_landArea",
                    "type": "uint256"
                    },
                    {
                    "indexed": false,
                    "internalType": "string",
                    "name": "_aadharNumber",
                    "type": "string"
                    },
                    {
                    "indexed": false,
                    "internalType": "string",
                    "name": "_bankAccount",
                    "type": "string"
                    },
                    {
                    "indexed": false,
                    "internalType": "uint256",
                    "name": "_subsidyAmount",
                    "type": "uint256"
                    },
                    {
                    "indexed": false,
                    "internalType": "string",
                    "name": "_schemeName",
                    "type": "string"
                    },
                    {
                    "indexed": false,
                    "internalType": "bool",
                    "name": "_isRegistered",
                    "type": "bool"
                    }
                ],
                "name": "GetDetails",
                "type": "event"
                },
                {
                "anonymous": false,
                "inputs": [
                    {
                    "indexed": false,
                    "internalType": "address",
                    "name": "_farmer",
                    "type": "address"
                    },
                    {
                    "indexed": false,
                    "internalType": "uint256",
                    "name": "_subsidyAmount",
                    "type": "uint256"
                    },
                    {
                    "indexed": false,
                    "internalType": "string",
                    "name": "_schemeName",
                    "type": "string"
                    },
                    {
                    "indexed": false,
                    "internalType": "bool",
                    "name": "_isSchemeApproved",
                    "type": "bool"
                    }
                ],
                "name": "SchemeApproved",
                "type": "event"
                },
                {
                "inputs": [],
                "name": "farmerCount",
                "outputs": [
                    {
                    "internalType": "uint256",
                    "name": "",
                    "type": "uint256"
                    }
                ],
                "stateMutability": "view",
                "type": "function",
                "constant": true
                },
                {
                "inputs": [
                    {
                    "internalType": "address",
                    "name": "",
                    "type": "address"
                    }
                ],
                "name": "farmers",
                "outputs": [
                    {
                    "internalType": "uint256",
                    "name": "id",
                    "type": "uint256"
                    },
                    {
                    "internalType": "string",
                    "name": "name",
                    "type": "string"
                    },
                    {
                    "internalType": "uint256",
                    "name": "landArea",
                    "type": "uint256"
                    },
                    {
                    "internalType": "string",
                    "name": "aadharNumber",
                    "type": "string"
                    },
                    {
                    "internalType": "string",
                    "name": "bankAccount",
                    "type": "string"
                    },
                    {
                    "internalType": "uint256",
                    "name": "subsidyAmount",
                    "type": "uint256"
                    },
                    {
                    "internalType": "string",
                    "name": "schemeName",
                    "type": "string"
                    },
                    {
                    "internalType": "bool",
                    "name": "isRegistered",
                    "type": "bool"
                    }
                ],
                "stateMutability": "view",
                "type": "function",
                "constant": true
                },
                {
                "inputs": [],
                "name": "government",
                "outputs": [
                    {
                    "internalType": "address",
                    "name": "",
                    "type": "address"
                    }
                ],
                "stateMutability": "view",
                "type": "function",
                "constant": true
                },
                {
                "inputs": [],
                "name": "totalSubsidyAmount",
                "outputs": [
                    {
                    "internalType": "uint256",
                    "name": "",
                    "type": "uint256"
                    }
                ],
                "stateMutability": "view",
                "type": "function",
                "constant": true
                },
                {
                "inputs": [
                    {
                    "internalType": "address",
                    "name": "_farmer",
                    "type": "address"
                    },
                    {
                    "internalType": "uint256",
                    "name": "_subsidyAmount",
                    "type": "uint256"
                    },
                    {
                    "internalType": "string",
                    "name": "_schemeName",
                    "type": "string"
                    },
                    {
                    "internalType": "bool",
                    "name": "_isSchemeApproved",
                    "type": "bool"
                    }
                ],
                "name": "updateSubsidyAmount",
                "outputs": [],
                "stateMutability": "nonpayable",
                "type": "function"
                },
                {
                "inputs": [
                    {
                    "internalType": "string",
                    "name": "_name",
                    "type": "string"
                    },
                    {
                    "internalType": "uint256",
                    "name": "_landArea",
                    "type": "uint256"
                    },
                    {
                    "internalType": "string",
                    "name": "_aadharNumber",
                    "type": "string"
                    },
                    {
                    "internalType": "string",
                    "name": "_bankAccount",
                    "type": "string"
                    },
                    {
                    "internalType": "string",
                    "name": "_schemeName",
                    "type": "string"
                    }
                ],
                "name": "registerFarmer",
                "outputs": [],
                "stateMutability": "nonpayable",
                "type": "function"
                },
                {
                "inputs": [
                    {
                    "internalType": "address",
                    "name": "_farmer",
                    "type": "address"
                    }
                ],
                "name": "getFarmerDetails",
                "outputs": [
                    {
                    "internalType": "uint256",
                    "name": "",
                    "type": "uint256"
                    },
                    {
                    "internalType": "string",
                    "name": "",
                    "type": "string"
                    },
                    {
                    "internalType": "uint256",
                    "name": "",
                    "type": "uint256"
                    },
                    {
                    "internalType": "string",
                    "name": "",
                    "type": "string"
                    },
                    {
                    "internalType": "string",
                    "name": "",
                    "type": "string"
                    },
                    {
                    "internalType": "uint256",
                    "name": "",
                    "type": "uint256"
                    },
                    {
                    "internalType": "string",
                    "name": "",
                    "type": "string"
                    },
                    {
                    "internalType": "bool",
                    "name": "",
                    "type": "bool"
                    }
                ],
                "stateMutability": "nonpayable",
                "type": "function"
                }
            ]
            const Address = "0xc00A0445F71e96613FE6296046eA3963cb5351f7";
            window.web3 = await new Web3(window.ethereum);
            window.contract = await new window.web3.eth.Contract(ABI, Address);
            document.getElementById("walletid").innerHTML = account;
        }
        init();
        var priObj = function (obj) {
            var string = 'Farmer ID: ';
            let i = 0;
            for (var prop in obj) {
                if (typeof obj[prop] == 'string') {
                    string += obj[prop] + '</br>';
                    i++;
                }
                if(i==1)
                {
                    string += 'Farmer Name: ';
                }
                if(i==2)
                {
                    string += 'Acres of Land: ';
                }
                if(i==3)
                {
                    string += 'Aadhar Number: ';
                }
                if(i==4)
                {
                    string += 'Bank Account Number: ';
                }
                if(i==5)
                {
                    string += 'Approved Amount: ₹ ';
                }
                if(i==6)
                {
                    string += 'Approval Status: ';
                }
            }
            return string;
        }
        var x = <?php echo json_encode($wallet_user); ?>;
        const readfarmer = async () => {
            if(account==<?php echo $wallet_user?>){
                const data = await window.contract.methods.getFarmerDetails(x).call();
                document.getElementById("dataArea").innerHTML = priObj(data);
            }
            else{
                document.getElementById("dataArea").innerHTML = "The Wallet used is not this user's wallet"
            }
        }

        const registerfarmer = async () => {
            const myName = document.getElementById("name").value;
            const myLandarea = document.getElementById("landarea").value;
            const myAadharno = document.getElementById("aadharno").value;
            const myBankacc = document.getElementById("bankacc").value;
            const mySchemename = document.getElementById("schemename").value;
            if(account==<?php echo $wallet_user?>){
                await window.contract.methods.registerFarmer(myName, myLandarea, myAadharno, myBankacc, mySchemename).send({ from: account });
            }
            else{
                document.getElementById("dataArea").innerHTML = "The Wallet used is not this user's wallet"
            }
        }
    </script>   
</body>

</html>