pipeline {
    agent any

    environment {
        COMPOSER_CACHE_DIR = "${WORKSPACE}/.composer"
        DOCKER_COMPOSE_FILE = '../docker-compose.yml'
    }

    stages {
        stage('Checkout') {
            steps {
                // Check out the code from the repository
                git 'https://github.com/jyhedHR/abshore.git'
            }
        }
        stage('Build Docker Containers') {
            steps {
                // Build the Docker containers
                sh 'docker-compose -f ${DOCKER_COMPOSE_FILE} build'
            }
        }
        stage('Start Docker Containers') {
            steps {
                // Start the Docker containers
                sh 'docker-compose -f ${DOCKER_COMPOSE_FILE} up -d'
            }
        }
        stage('Install Dependencies') {
            steps {
                // Install PHP dependencies using Composer inside the PHP container
                sh 'docker-compose -f ${DOCKER_COMPOSE_FILE} exec php74-service composer install'
            }
        }
        stage('Run Tests') {
            steps {
                // Run PHPUnit tests inside the PHP container
                sh 'docker-compose -f ${DOCKER_COMPOSE_FILE} exec php74-service php bin/phpunit'
            }
        }
        stage('Deploy') {
            steps {
                // Deploy your application (this is an example)
                sh 'echo "Deploying application..."'
                // Add your deployment commands here
            }
        }
        stage('Stop Docker Containers') {
            steps {
                // Stop and remove the Docker containers
                sh 'docker-compose -f ${DOCKER_COMPOSE_FILE} down'
            }
        }
    }
}
