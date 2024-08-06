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
        stage('Copy .env File') {
            steps {
                script {
                    sh 'cat /mnt/env-aset/posref/development.env'
                    sh 'cp /mnt/env-aset/posref/development.env aplikasi-pos/.env.development'
                    sh 'cp /mnt/env-aset/posref/development.env aplikasi-pos/.env.production'
                    sh 'cat aplikasi-pos/.env.production'
                }
            }
        }
        stage('Copy vendor File') {
            steps {
                script {
                    sh 'cp -r /mnt/vendor aplikasi-pos/'
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
		stage('Create Folder') {
            steps {
				script {
                dir('aplikasi-pos') {
					if (!ileExists('sess_kopmart')) {
						sh 'mkdir sess_kopmart'
						sh 'mkdir uploads'
						sh 'mkdir assets/barcode'
						}  
                	}
				}
            }
        }
		// stage('Build Dockercompose') {
        //     steps {
        //         dir('aplikasi-pos') {
        //             sh 'docker-compose up -d'
        //         }
		// 		//nginx
		// 		// dir('aplikasi-pos') {
        //         //     sh 'docker build -t posref-nginx-service /docker/nginx/.'
        //         // }
        //     }
        // }
    }
}
