pipeline {
    agent any

    environment {
        DOCKER_COMPOSE_FILE = 'docker-compose.yml'
    }

    stages {
        stage('Checkout') {
            steps {
                // Check out the code from the repository
                git 'https://github.com/jyhedHR/abshore.git'
            }
        }
        stage('Start Docker Containers') {
            steps {
                // Start the Docker containers in detached mode
                sh "docker-compose -f ${DOCKER_COMPOSE_FILE} up -d"

                // Wait for containers to be fully up (adjust the sleep time as needed)
                sh 'sleep 30'
            }
        }
        stage('Open Main Page') {
            steps {
                // Assuming your Symfony app runs on port 80 in the container
                // You might need to adjust this URL based on your setup
                sh 'curl -I http://localhost:80'
            }
        }
        stage('Stop Docker Containers') {
            steps {
                // Stop and remove the Docker containers
                sh "docker-compose -f ${DOCKER_COMPOSE_FILE} down"
            }
        }
    }
}
