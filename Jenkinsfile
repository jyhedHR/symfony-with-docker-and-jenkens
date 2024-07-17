pipeline {
    agent any // Runs on any available agent (Jenkins node)

    stages {
        stage('Checkout') {
            steps {
                // Checkout code from a Git repository
                git 'https://github.com/jyhedHR/abshore.git'
            }
        }
        
        stage('Build Docker Image') {
            steps {
                // Build Docker image
                script {
                    docker.build('my-image:latest')
                }
            }
        }

        stage('Run Tests') {
            steps {
                // Example: Run tests inside a Docker container
                script {
                    docker.image('my-image:latest').inside {
                        sh 'npm install' // Replace with your test command
                    }
                }
            }
        }

        stage('Deploy') {
            steps {
                // Example: Deploy to a server
                sh 'ssh user@server "docker-compose up -d"'
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
