pipeline {
    agent any

    environment {
        DOCKER_COMPOSE_FILE = 'docker-compose.yml'
    }

    stages {
        stage('Checkout') {
            steps {
                // Check out the code from the 'test1' branch of the repository
                git branch: 'test1', url: 'https://github.com/jyhedHR/abshore.git'
            }
        }
        stage('Start Docker Containers') {
    steps {
        script {
            // Start the Docker containers in detached mode
            sh "docker-compose up --build"

            // Wait for containers to be fully up (adjust the sleep time as needed)
            sh 'timeout 30' // Replace 'sleep 30' with 'timeout 30' on Windows
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
