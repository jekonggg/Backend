<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome To Our Shop!</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Define a keyframe animation for fading in */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px); /* Optional: Add a slight vertical animation */
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color:  #FFFFFF;
            text-align: center; /* Center aligns the text in the body */
            line-height: 1.6;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #EE2222;
            padding: 20px 40px;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header a {
            color: white;
            text-decoration: none;
            margin: 0 20px;
            font-weight: bold;
            position: relative;
            transition: color 0.3s ease-in-out;
            padding-bottom: 8px;
        }

        .header a.active {
            color: black;
        }

        .header a::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: 0;
            width: 0;
            height: 4px;
            background-color: black;
            transition: width 0.3s ease-in-out, transform 0.3s ease-in-out;
            transform-origin: left;
        }

        .header a.active::after {
            width: 100%;
            transform: scaleX(1);
        }

        .logo {
            padding: 300px 0;
            text-align: center; /* Center the logo within its container */
            opacity: 0; /* Initially hide the logo */
            animation: fadeIn 1s ease forwards; /* Apply fadeIn animation to logo */
        }

        .logo img {
            width: 800px; /* Set the desired width */
            height: auto; /* Maintain aspect ratio */
        }

        .content {
            padding: 70px;
            padding-bottom: 120px;
            margin-bottom: 60px;
            text-align: left; /* Align text to left for content sections */
            min-height: 100vh; /* Set a minimum height of 100% viewport height */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            opacity: 0; /* Initially hide content */
            transform: translateY(20px); /* Optional: Add a slight initial animation */
            animation: fadeIn 0.5s ease forwards; /* Apply fadeIn animation */
        }

        .content h1 {
            font-size: 3em;
            margin-bottom: 20px;
            text-align: center; /* Center align headings within content sections */
        }

        .content p {
            font-size: 1.4em;
            max-width: 800px;
            margin: 20px auto; /* Center align paragraphs within content sections */
            text-align: justify; /* Justify text for better readability */
        }

        .underline {
            width: 100px;
            height: 4px;
            background-color: #EE2222;
            margin: 10px auto;
        }

        .team-members {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
}

.team-member {
    flex: 1 1 300px; /* Each member container will have a minimum width of 300px */
    text-align: center;
    margin: 20px;
    position: relative;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    max-width: 300px; /* Limit maximum width to prevent stretching */
}

.team-member img {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border-radius: 50%;
    margin-bottom: 10px;
    transition: opacity 0.3s;
}

.team-member:hover img {
    opacity: 0.3;
}

.team-member .social-link {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    opacity: 0;
    transition: opacity 0.3s;
    font-size: 2em;
    color: #3b5998;
}

.team-member:hover .social-link {
    opacity: 1;
}


    </style>
</head>
<body>
<div class="header">
    <a class="underline" href="login.php">Go to Shop</a>
    <div>
        <a onclick="scrollToSection('project-overview')">Project Overview</a>
        <a id="goal-link" onclick="scrollToSection('goal')">Goal</a>
        <a onclick="scrollToSection('about-us')">About Us</a>
        <a onclick="scrollToSection('the-team')">The Team</a>
    </div>
</div>
<div class="logo">
    <img src="Repositories/logo.PNG" alt="Auto Supply Logo">
</div>
<div class="content" id="project-overview">
    <h1>PROJECT OVERVIEW</h1>
    <div class="underline"></div>
    <p>
        The automotive industry in the Philippines is witnessing a burgeoning demand for high-quality electrical supplies, particularly in the realm of auto electrical systems. Our project aims to address this need by developing a dedicated online platform specializing in auto electrical supplies. This comprehensive website will cater to both internal and external electrical components essential for vehicle construction and maintenance.
    </p>
</div>
<div class="content" id="goal">
    <h1>GOAL</h1>
    <div class="underline"></div>
    <p>
        Our primary goal is to establish a seamless and user-friendly online shopping experience for automotive enthusiasts, professional mechanics, car repair shops, and car owners alike. By providing a centralized platform, we aim to streamline the procurement process of essential auto electrical products, ensuring easy access to a diverse range of items such as wiring, connectors, fuses, lights, and more.
    </p>
</div>
<div class="content" id="about-us">
    <h1>ABOUT US</h1>
    <div class="underline"></div>
    <p>
        We are passionate about revolutionizing the way auto electrical supplies are accessed and procured in the Philippines. Committed to excellence and customer satisfaction, our team combines expertise in automotive technology and e-commerce to create a platform that meets the evolving needs of our users. Our mission is to enhance vehicle performance and maintenance efficiency through reliable and innovative solutions.
    </p>
</div>
<div class="content" id="the-team">
    <h1>THE TEAM</h1>
    <div class="underline"></div>
    <p style="text-align: center;">
        Meet the dedicated team behind our project.
    </p>
    <div class="team-members">
        <div class="team-member">
            <img src="Repositories/andrew.jpg" alt="Andrew James F. Potenciano">
            <h3>Andrew James F. Potenciano</h3>
            <h5>Front-End Developer</h5>
            <a href="https://www.facebook.com/profile.php?id=100055293679336" class="social-link" target="_blank"><i class="fab fa-facebook"></i></a>
        </div>
        <div class="team-member">
            <img src="Repositories/patrick.jpg" alt="James Patrick Rojas">
            <h3>James Patrick Rojas</h3> 
            <h5>Lead Front-End Developer</h5>
            <a href="https://www.facebook.com/patpatrojas" class="social-link" target="_blank"><i class="fab fa-facebook"></i></a>
        </div>
        <div class="team-member">
            <img src="Repositories/jc.jpg" alt="Jc Ramos">
            <h3>Jc Ramos</h3>
            <h5 style="text-align: center;">
                Team Leader<br> Back-End Developer <br> Front-End Developer
            </h5>
            <a href="https://www.facebook.com/jcjayceeramos" class="social-link" target="_blank"><i class="fab fa-facebook"></i></a>
        </div>
        <div class="team-member">
            <img src="Repositories/gab.jpg" alt="Gab Villon">
            <h3>Gab Villon</h3>
            <h5>Back-End Developer</h5>
            <!-- No Facebook link for Gab -->
        </div>
    </div>
</div>
<script>
    function scrollToSection(sectionId) {
        var offset = 100; // Adjust this value as needed to move the section up
        var element = document.getElementById(sectionId);
        var elementPosition = element.getBoundingClientRect().top;
        var offsetPosition = elementPosition + window.pageYOffset + offset;

        window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth'
        });
        setActiveLink(sectionId);
    }

    function setActiveLink(sectionId) {
        var links = document.querySelectorAll('.header a');
        links.forEach(function(link) {
            link.classList.remove('active');
        });
        document.querySelector('.header a[onclick="scrollToSection(\'' + sectionId + '\')"]').classList.add('active');
    }

    function addFadeInClass() {
        var elements = document.querySelectorAll('.content');
        elements.forEach(function(element) {
            if (isElementInViewport(element)) {
                element.classList.add('fade-in');
            }
        });
    }

    function isElementInViewport(el) {
        var rect = el.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }

    window.addEventListener('scroll', addFadeInClass);
    window.addEventListener('load', function() {
        addFadeInClass(); // Ensure initial elements in view get the animation
        document.querySelector('.logo').classList.add('fade-in'); // Ensure logo gets the fade-in animation
    });
</script>
</body>
</html>
