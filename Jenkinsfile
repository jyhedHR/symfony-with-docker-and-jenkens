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
                  
                }
            }
        }

        stage('Run Symfony Commands') {
            steps {
                script {
                  

                    // Clear Symfony cache
                    bat "docker-compose -f ${DOCKER_COMPOSE_FILE} exec php php app/bin/console cache:clear --env=${SYMFONY_ENV} --no-warmup"

                    // Optionally warm up Symfony cache
                    // bat "docker-compose -f ${DOCKER_COMPOSE_FILE} exec php php bin/console cache:warmup --env=${SYMFONY_ENV}"

                    // Run Symfony migrations (if using Doctrine)
                    bat "docker-compose -f ${DOCKER_COMPOSE_FILE} exec php php app/bin/console doctrine:migrations:migrate --env=${SYMFONY_ENV} --no-interaction"

                    // Compile assets (if needed)
                    bat "docker-compose -f ${DOCKER_COMPOSE_FILE} exec php php app/bin/console assets:install public"
                }
            }
        }

        stage('Compile and Display User List') {
            steps {
                script {
                    // Run Symfony command to generate user list (replace with your controller and action)
                    bat "docker-compose -f ${DOCKER_COMPOSE_FILE} exec php php app/bin/console app:user:list"

                    // Optionally, trigger Twig rendering or view the generated content
                    // Example: bat "docker-compose -f ${DOCKER_COMPOSE_FILE} exec php php bin/console twig:render user/list.html.twig"
                }
            }
        }

        stage('Run Tests') {
            steps {
                script {
                    // Run Symfony tests (replace with your test command)
                    bat "docker-compose -f ${DOCKER_COMPOSE_FILE} exec php php app/bin/phpunit"
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
