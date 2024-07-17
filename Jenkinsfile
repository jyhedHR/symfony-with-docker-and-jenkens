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
                    bat 'timeout /t 30'
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
