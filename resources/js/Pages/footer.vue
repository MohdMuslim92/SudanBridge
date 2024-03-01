<template>
    <footer class="bg-gray-900 text-white py-8">
        <div class="container mx-auto flex flex-col md:flex-row justify-between items-center px-4">
            <div class="flex items-center space-x-4">
                <a href="https://github.com/MohdMuslim92" target="_blank" class="text-gray-300 hover:text-white">
                    <img src="../../../public/svg/github.svg" alt="Github" class="h-6 w-6 invert-colors" />
                </a>
                <a href="https://twitter.com/MohdMuslim92" target="_blank" class="text-gray-300 hover:text-white">
                    <img src="../../../public/svg/X.svg" alt="Twitter X" class="h-6 w-6 invert-colors" />
                </a>
                <a href="https://www.linkedin.com/in/mohdmuslim92/" target="_blank" class="text-gray-300 hover:text-white">
                    <img src="../../../public/svg/linkedin.svg" alt="LinkedIn" class="h-6 w-6 invert-colors" />
                </a>
            </div>
            <nav class="flex flex-wrap justify-center mt-4 md:mt-0">
                <a href="#" class="mx-2 text-gray-300 hover:text-white">About Us</a>
                <a href="#" class="mx-2 text-gray-300 hover:text-white">Contact</a>
                <a href="#" class="mx-2 text-gray-300 hover:text-white">FAQs</a>
            </nav>
        </div>
    </footer>
</template>

<style>
.invert-colors {
    filter: invert(1);
}

/* Footer styling */
footer {
    height: 100px; /* Set a fixed height for the footer */
    background-color: #333;
    color: #fff; /* Adjust text color */
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 20px;
}

/* Adjust link styling */
footer a {
    color: #fff;
    text-decoration: none;
    margin-right: 10px;
    transition: color 0.3s ease;
}

footer a:hover {
    color: #ccc;
}
</style>
