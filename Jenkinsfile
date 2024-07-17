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

                   bat 'ping -n 31 127.0.0.1 > nul'
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

        stage('Compile and Display User List') {
            steps {
                script {
                    // Run Symfony command to generate user list (adjust with your actual Symfony command)
                    bat "docker-compose -f ${DOCKER_COMPOSE_FILE} exec php74-service php bin/console app:user:list"

                    // Optionally, trigger Twig rendering or view the generated content
                    // Example: bat "docker-compose -f ${DOCKER_COMPOSE_FILE} exec php74-container php bin/console twig:render user/list.html.twig"
                }
            }
        }

        stage('Run Tests') {
            steps {
                script {
                    // Run Symfony tests (replace with your test command)
                    bat "docker-compose -f ${DOCKER_COMPOSE_FILE} exec php74-service php bin/phpunit"
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
