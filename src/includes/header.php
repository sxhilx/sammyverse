<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php $firstName = $_SESSION['firstName'];
                $lastName = $_SESSION['lastName'];
                 echo htmlspecialchars($firstName . ' ' . $lastName); ?>'s Profile</title>
    <link rel="stylesheet" href="/public/output.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <base href="/sammyverse/src/views/">
</head>
<body>
    <section class="flex flex-col md:flex-row h-full m-auto text-[#edeff5]">
        <div class="w-full h-full md:w-[90%] md:h-screen bg-[#000000] p-8 md:mr-[10%] overflow-y-auto min-h-screen">