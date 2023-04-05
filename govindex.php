<?php include ( "db.php" ); ?>
<?php 
ob_start();
session_start();
if (!isset($_SESSION['user_login'])) {
	$user = "";
    $uname_db="";
    $wallet_gov="";
}
else {
	$user = $_SESSION['user_login'];
	$result = mysqli_query($con,"SELECT * FROM user WHERE id='$user'");
		$get_user_email = mysqli_fetch_assoc($result);
			$uname_db = $get_user_email['firstName'];
            $wallet_gov = $get_user_email['Wallet_address'];
}
if($wallet_gov!="0xc6C7b469701A027C905B20f5C31fa1D6F3068f42"){
    session_destroy();
    setcookie('user_login', '', 0, "/");
    header("Location: index.php");
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
        <p class="titles">APPROVE SCHEME APPLICATIONS</p><br><br>
        <label for="faraddr">Farmer Wallet Address:</label>
        <input type="text" id="faraddr"><br><br>
        <label for="subamt">Subsidy Amount in Ruppees:</label>
        <input type="text" id="subamt"><br><br>
        <label for="schemename">Scheme Name:</label>
        <input type="text" id="schemename"><br><br>
        <label>Is scheme approved:</label><br><br>
        <input type="radio" id="scapr" name="yes" value="1">
        <label for="yes">Yes</label><br>
        <input type="radio" id="scapr" name="no" value="0">
        <label for="no">No</label><br>
        
        <button onclick="updatedetails()" id="updatedetails" class="uisignupbutton">Update Details</button><br><br><br>
        <button onclick="farmercount()" class="uisignupbutton">Total Farmers Count</button>
        <button onclick="totalsub()" class="uisignupbutton">Total Subsidy Amount</button> <br>
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
        }
        init();
        const totalsub = async () => {
            const data = await window.contract.methods.totalSubsidyAmount().call();
            document.getElementById("dataArea").innerHTML = `Total Subsidy Amount is: ₹ ${data}`;
        }

        const farmercount = async () => {
            const data = await window.contract.methods.farmerCount().call();
            document.getElementById("dataArea").innerHTML = `Total Farmers Applied is: ${data} Farmers`;
        }

        const updatedetails = async () => {
            const myFarmaddr = document.getElementById("faraddr").value;
            const mySubamt = document.getElementById("subamt").value;
            const mySchemename = document.getElementById("schemename").value;
            const myApproval = document.getElementById("scapr").value;
            if(account==<?php echo $wallet_gov?>){
                await window.contract.methods.updateSubsidyAmount(myFarmaddr, mySubamt, mySchemename, myApproval).send({ from: account });
            }
            else{
                document.getElementById("dataArea").innerHTML = "This can be done only using the Government's Wallet"
            }    
        }
    </script>
</body>

</html>