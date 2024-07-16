pipeline {
    agent any

    environment {
        DOCKER_COMPOSE_FILE = 'docker-compose.yml'
        SYMFONY_ENV = 'prod' // Adjust as per your Symfony environment (dev, prod, etc.)
    }

    stages {
        stage('Checkout') {
            steps {
                // Check out the code from the 'test1' branch of the repository
                git branch: 'test1', url: 'https://github.com/jyhedHR/abshore.git'
            }
        }

        stage('Build and Start Docker Containers') {
            steps {
                script {
                    // Start the Docker containers in detached mode using 'docker-compose up'
                    bat "docker-compose -f ${DOCKER_COMPOSE_FILE} up --build -d"
                    
                    // Wait for containers to be fully up (adjust the sleep time as needed)
                    bat 'timeout /t 30 >nul'
                }
            }
        }

        stage('Run Symfony Commands') {
            steps {
                script {
                    // Install dependencies (assuming using Composer)
                    bat "docker-compose -f ${DOCKER_COMPOSE_FILE} exec php composer install --no-interaction --optimize-autoloader"

                    // Clear Symfony cache
                    bat "docker-compose -f ${DOCKER_COMPOSE_FILE} exec php php bin/console cache:clear --env=${SYMFONY_ENV} --no-warmup"

                    // Warm up Symfony cache (optional)
                    bat "docker-compose -f ${DOCKER_COMPOSE_FILE} exec php php bin/console cache:warmup --env=${SYMFONY_ENV}"

                    // Run Symfony migrations (if using Doctrine)
                    bat "docker-compose -f ${DOCKER_COMPOSE_FILE} exec php php bin/console doctrine:migrations:migrate --env=${SYMFONY_ENV} --no-interaction"
                }
            }
        }

        stage('Open Main Page') {
            steps {
                // Assuming your Symfony app runs on port 80 in the container
                // You might need to adjust this URL based on your setup
                bat 'curl -I http://localhost:8081'
            }
        }

        stage('Stop Docker Containers') {
            steps {
                // Stop and remove the Docker containers
                bat "docker-compose -f ${DOCKER_COMPOSE_FILE} down"
            }
        }
    }
}
