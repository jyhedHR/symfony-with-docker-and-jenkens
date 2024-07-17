pipeline {
    agent any // Runs on any available agent (Jenkins node)

    environment {
        DOCKER_COMPOSE_FILE = 'docker-compose.yml'
        SYMFONY_ENV = 'prod' // Adjust as per your Symfony environment (dev, prod, etc.)
    }

    stages {
        stage('Checkout') {
            steps {
                git credentialsId: '12345',
                    branch: 'test1', 
                    url: 'https://github.com/jyhedHR/abshore.git'
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

        stage('Run Tests') {
            steps {
                script {
                    // Run Symfony tests (replace with your test command)
                    bat "docker-compose -f ${DOCKER_COMPOSE_FILE} exec php php bin/phpunit"
                }
            }
        }

        stage('Stop Docker Containers') {
            steps {
                // Stop and remove the Docker containers
                bat "docker-compose -f ${DOCKER_COMPOSE_FILE} down"
            }
        }
    }
    
    post {
        success {
            echo 'Pipeline successfully completed!'
        }
        failure {
            echo 'Pipeline failed :('
        }
    }
}
