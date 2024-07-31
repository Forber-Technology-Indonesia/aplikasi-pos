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
                        sh 'docker stop posref1'
                        sh 'docker rm posref1'
                    } catch (Exception e) {
                        echo "Container posref1 was not running or could not be stopped/removed: ${e}"
                    }
                }
            }
        }
        stage('Image Renewal') {
            steps {
                script {
                    try {
                        sh 'docker rmi posref-nginx-service'
                    } catch (Exception e) {
                        echo "Image posref-nginx-service could not be removed: ${e}"
                    }
                }
            }
        }
        stage('Build Docker New Image') {
            steps {
                dir('aplikasi-pos') {
                    sh 'docker build -t posref-nginx-service .'
                }
            }
        }
        stage('Run New Container') {
            steps {
                sh 'docker run -d --name posref1  -p 8001:80 posref-nginx-service'
            }
        }
    }
}