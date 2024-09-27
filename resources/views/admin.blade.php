<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Device Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2>Device Management</h2>
    <table class="table table-bordered" id="deviceTable">
        <thead>
        <tr>
            <th>ID</th>
            <th>Model</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody id="deviceList">
        <!-- Devices will be populated here -->
        </tbody>
    </table>

    <button id="generateTokenBtn" class="btn btn-primary">Generate Enrollment Token</button>
    <div id="qrCode"></div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>
<script>
    $(document).ready(function() {
        // Fetch devices
        $.get("/devices/{enterpriseId}", function(data) {
            let deviceList = $("#deviceList");
            data.forEach(function(device) {
                deviceList.append(`
                    <tr>
                        <td>${device.deviceId}</td>
                        <td>${device.model}</td>
                        <td>${device.state}</td>
                        <td>
                            <button onclick="lockDevice('${device.deviceId}')">Lock</button>
                            <button onclick="unlockDevice('${device.deviceId}')">Unlock</button>
                            <button onclick="wipeDevice('${device.deviceId}')">Wipe</button>
                            <button onclick="showMessage('${device.deviceId}')">Show Message</button>
                            <button onclick="removeDevice('${device.deviceId}')">Remove</button>
                        </td>
                    </tr>
                `);
            });
        });

        // Generate Enrollment Token
        $("#generateTokenBtn").click(function() {
            $.get("/enrollment/{enterpriseId}", function(data) {
                let qrCode = $("#qrCode");
                qrCode.qrcode(data.qrCode);  // Replace with actual field for QR Code
            });
        });
    });

    function lockDevice(deviceId) {
        $.post(`/devices/{enterpriseId}/lock/${deviceId}`);
    }

    function unlockDevice(deviceId) {
        // API implementation required for unlock
    }

    function wipeDevice(deviceId) {
        $.post(`/devices/{enterpriseId}/wipe/${deviceId}`);
    }

    function showMessage(deviceId) {
        let message = prompt("Enter message to show:");
        $.post(`/devices/{enterpriseId}/message/${deviceId}`, {message: message});
    }

    function removeDevice(deviceId) {
        $.ajax({
            url: `/devices/{enterpriseId}/${deviceId}`,
            type: 'DELETE',
            success: function(result) {
                alert("Device removed");
            }
        });
    }
</script>
</body>
</html>
