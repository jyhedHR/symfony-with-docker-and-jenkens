pipeline {
    agent any

    environment {
        DOCKER_COMPOSE_FILE = 'docker-compose.yml'
        DOCKER_IMAGE = 'jyhedhr/abshore'
        DOCKER_CREDENTIALS_ID = '2020' // Your Docker Hub credentials ID
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

        stage('Build Docker Image') {
            steps {
                script {
                    // Build the Docker image
                    bat "docker build -t ${DOCKER_IMAGE} ."
                }
            }
        }

        stage('Push Docker Image') {
            steps {
                script {
                   withCredentials([usernamePassword(credentialsId: "${DOCKER_CREDENTIALS_ID}", passwordVariable: 'DOCKER_PASSWORD', usernameVariable: 'DOCKER_USERNAME')]) {
                        // Log in to Docker Hub
                        bat "docker login -u %DOCKER_USERNAME% -p %DOCKER_PASSWORD% "

                        // Push the Docker image to Docker Hub
                        bat "docker push ${DOCKER_IMAGE}"
                   }
            }
        }
        }

        stage('Build and Start Docker Containers') {
            steps {
                script {
                    // Start the Docker containers in detached mode using 'docker-compose up'
                    
                    bat "docker-compose -f ${DOCKER_COMPOSE_FILE} up --build -d"

                    // List all running containers to capture their names
                    def containers = bat(script: 'docker ps --format "{{.Names}}"', returnStdout: true).trim().split('\n')
                    echo "Running containers: ${containers}"
                     bat "docker-compose exec php74-service pwd"
                }
            }
        }
        stage('Install Drivers for Panther') {
            steps {
                script {
                    // Install bdi and detect the drivers
                    bat "docker-compose -f ${DOCKER_COMPOSE_FILE} exec php74-service composer require --dev dbrekelmans/bdi --working-dir=/var/www/project/app"
                    bat "docker-compose -f ${DOCKER_COMPOSE_FILE} exec php74-service app/vendor/bin/bdi detect drivers"
                }
            }
        }

        stage('Run Symfony Commands') {
            steps {
                script {
                    // Install dependencies (assuming using Composer)
                    bat "docker-compose -f ${DOCKER_COMPOSE_FILE} exec php74-service composer install --no-interaction --optimize-autoloader"
                    // Clear Symfony cache
                    bat "docker-compose -f ${DOCKER_COMPOSE_FILE} exec php74-service php bin/console cache:clear --env=${SYMFONY_ENV} --no-warmup"
                }
            }
        }

        stage('Run Application') {
            steps {
                script {
                    // Ensure the Symfony server or application is running correctly
                    bat "curl -f http://localhost:8088/user" // Adjust the URL and port as per your setup
                }
            }
        }
         stage('Run PHPUnit Tests') {
            steps {
                script {
                    // Run PHPUnit tests
                    bat "docker-compose -f ${DOCKER_COMPOSE_FILE} exec php74-service vendor/bin/phpunit"
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
