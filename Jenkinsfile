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
                        sh 'docker rmi posref-bitnami-service'
                    } catch (Exception e) {
                        echo "Image posref-bitnami-service could not be removed: ${e}"
                    }
                }
            }
        }
        stage('Build Docker New Image') {
            steps {
                dir('aplikasi-pos') {
                    sh 'docker build -t posref-bitnami-service .'
                }
            }
        }
        stage('Run New Container') {
            steps {
                sh 'docker run -d --name posref1  -p 8001:8001 posref-bitnami-service'
            }
        }
    }
}
