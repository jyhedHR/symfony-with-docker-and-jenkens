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
                    
               

                    // List all running containers to capture their names
                    def containers = bat(script: 'docker ps --format "{{.Names}}"', returnStdout: true).trim().split('\n')
                    echo "Running containers: ${containers}"

                 
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
            stage('Visit Site and Confirm') {
            steps {
                 input message: 'Visit the site and confirm it is accessible, then proceed to shut down Docker containers.', ok: 'Proceed'
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
