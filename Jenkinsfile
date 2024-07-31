pipeline {
    agent any

    stages {
        stage('Clone or Pull') {
            steps {
                script {
                    if (fileExists('aplikasi-pos')) {
                        dir('aplikasi-pos') {
                            sh 'git fetch'
                            sh 'git checkout main'
                            sh 'git pull origin main'
                        }
                    } else {
                        sh 'git clone -b main https://github.com/Forber-Technology-Indonesia/aplikasi-pos.git'
                    }
                }
            }
        }
        stage('Container Renewal') {
            steps {
                script {
                    try {
                        sh 'docker stop apache1'
                        sh 'docker rm apache1'
                    } catch (Exception e) {
                        echo "Container apache1 was not running or could not be stopped/removed: ${e}"
                    }
                }
            }
        }
        stage('Image Renewal') {
            steps {
                script {
                    try {
                        sh 'docker rmi apache_ci'
                    } catch (Exception e) {
                        echo "Image apache_ci could not be removed: ${e}"
                    }
                }
            }
        }
        stage('Build Docker New Image') {
            steps {
                dir('whatsappbot-with-gpt') {
                    sh 'docker build -t apache_ci .'
                }
            }
        }
        stage('Run New Container') {
            steps {
                sh 'docker run -d --name apache1  -p 8001:8001 apache_ci'
            }
        }
    }
    post {
        always {
            echo 'This will always run'
        }
        success {
            echo 'This will run only if successful'
        }
        failure {
            echo 'This will run only if failed'
        }
    }
}
