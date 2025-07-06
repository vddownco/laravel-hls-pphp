# Changelog

## v0.5.0 - 2025-07-06

### [0.5.0](https://github.com/achyutkneupane/laravel-hls/compare/v0.4.0...v0.5.0) (2025-07-06)

#### Features

* Middleware added in routes ([37b4183](https://github.com/achyutkneupane/laravel-hls/commit/37b4183e5bee04eb4903d8097bbafea3055bcce4))

## v0.4.0 - 2025-07-06

### [0.4.0](https://github.com/achyutkneupane/laravel-hls/compare/v0.3.0...v0.4.0) (2025-07-06)

#### Features

* HLSModel interface created ([76f3796](https://github.com/achyutkneupane/laravel-hls/commit/76f3796a1b1d678e610161cbd922e524011028ca))

#### Bug Fixes

* Process of model binding in routes updated ([#4](https://github.com/achyutkneupane/laravel-hls/issues/4)) ([a30a23e](https://github.com/achyutkneupane/laravel-hls/commit/a30a23e1c3442a0c4e8981da2811d8d857f24480))
* string-wise model binding for routes ([5869f73](https://github.com/achyutkneupane/laravel-hls/commit/5869f731089ca3a6946a9f14aa15033fdeb9fe8b))

#### Documentation

* Necessary documantation changes ([667b65f](https://github.com/achyutkneupane/laravel-hls/commit/667b65f60afe6dad4edf412ae2be8e232889991b))

## v0.3.0 - 2025-07-06

### [0.3.0](https://github.com/achyutkneupane/laravel-hls/compare/v0.2.0...v0.3.0) (2025-07-06)

#### Features

* Auto discovery for routes ([0f86780](https://github.com/achyutkneupane/laravel-hls/commit/0f867805fdb6e86cc9cb4af90e4ffadd87a0209e))

## v0.2.0 - 2025-07-06

### [0.2.0](https://github.com/achyutkneupane/laravel-hls/compare/v0.1.2...v0.2.0) (2025-07-06)

#### Features

* Controller added to handle the playlist, segments, and secrets ([3bcf3e5](https://github.com/achyutkneupane/laravel-hls/commit/3bcf3e5e36a73a16ed9856f6d169b80c2583f23d))
* Controller and route endpoints for accessing HLS playlist ([#3](https://github.com/achyutkneupane/laravel-hls/issues/3)) ([6dd2eee](https://github.com/achyutkneupane/laravel-hls/commit/6dd2eeedff28592e987886663a0d2e8d58fc1f16))
* Routes added to get playlist, segments, and secrets ([6c594d8](https://github.com/achyutkneupane/laravel-hls/commit/6c594d86f8426bdf7380bd7cee9559122b8268e5))

#### Documentation

* Docs added for accessing m3u8 playlist ([8fb00de](https://github.com/achyutkneupane/laravel-hls/commit/8fb00def6d28e0b04e15c4e07504a402da60ad24))

## v0.1.2 - 2025-07-05

### [0.1.2](https://github.com/achyutkneupane/laravel-hls/compare/v0.1.1...v0.1.2) (2025-07-05)

### Documentation

* link in license ([23a12b7](https://github.com/achyutkneupane/laravel-hls/commit/23a12b7f255cca59cc23c735e4db8faa9d86ae27))

## v0.1.1 - 2025-07-05

### [0.1.1](https://github.com/achyutkneupane/laravel-hls/compare/v0.1.0...v0.1.1) (2025-07-05)

### Documentation

* Basic documentation added for basic use case ([#2](https://github.com/achyutkneupane/laravel-hls/issues/2)) ([553fd7f](https://github.com/achyutkneupane/laravel-hls/commit/553fd7f92baf9ffeadb1d6ff9a359d1303659178))
* Configuration section added ([5a5563f](https://github.com/achyutkneupane/laravel-hls/commit/5a5563fb6cbab72f50b34ca6c47247042eac292f))
* Features and other sections in documentation ([2482e72](https://github.com/achyutkneupane/laravel-hls/commit/2482e728b4ba9b8b68613d81314fa075fdd1a90a))
* Installation documentation added ([71814d1](https://github.com/achyutkneupane/laravel-hls/commit/71814d130278fec1eb1870b120d8c23d82f53c02))
* Usage section added with trait use ([0c9d68a](https://github.com/achyutkneupane/laravel-hls/commit/0c9d68a74ee333ca6ae17bc943073b66e52dbfe7))

## v0.1.0 - 2025-07-05

### [0.1.0](https://github.com/achyutkneupane/laravel-hls/compare/v0.0.1...v0.1.0) (2025-07-05)

#### Features

* Classes to convert the video to HLS with config ([#1](https://github.com/achyutkneupane/laravel-hls/issues/1)) ([d74a433](https://github.com/achyutkneupane/laravel-hls/commit/d74a4330ec8c9dac703752e4ecb6d1dc6102ab88))
* Configuration defined for variables ([68ebb9f](https://github.com/achyutkneupane/laravel-hls/commit/68ebb9f545e9a358bacd0d861521f2a9f516469d))
* Conversion job checks for column before starting ([a2233a9](https://github.com/achyutkneupane/laravel-hls/commit/a2233a9a72a304196b2ffa31f1fcfc53a9e54c8b))
* Job for Converting and updating database cloned ([548ce73](https://github.com/achyutkneupane/laravel-hls/commit/548ce73b5b7d3a98f262ec5f9397d2b38dbafba1))
* Job for Updating conversion progress cloned ([a40dd30](https://github.com/achyutkneupane/laravel-hls/commit/a40dd30c162221ae493b509ece09d93ac78b2280))
* Observer added in Trait ([c0cd531](https://github.com/achyutkneupane/laravel-hls/commit/c0cd531f3d5546f8e157c9efa60a23d6e1b5f221))
* Observer to observe the created and updated events ([766d25c](https://github.com/achyutkneupane/laravel-hls/commit/766d25caf39643dd3ca70833e638764f9e095b3b))
* Trait methods to get the config variables ([d427a2b](https://github.com/achyutkneupane/laravel-hls/commit/d427a2b9f851f718e2f017bd38fc57cf49dcd157))

#### Bug Fixes

* config checking logic updated ([b138260](https://github.com/achyutkneupane/laravel-hls/commit/b138260bfec97b9e8e39e1895775e97781b1a17a))
* **test:** TestCase failed fixed ([43b10e5](https://github.com/achyutkneupane/laravel-hls/commit/43b10e57ef4ac1d324dd5104a319b43d564627ad))

#### Continuous Integration

* Extensions installed in workflow ([6ec1ba5](https://github.com/achyutkneupane/laravel-hls/commit/6ec1ba5b52339be6880088620d1a8a1174ff680a))

#### Code Refactoring

* All the static variables replaced by config variables ([77e0031](https://github.com/achyutkneupane/laravel-hls/commit/77e0031ce74b7fcb73d15af4b4b8c9b928186fc1))

#### Styles

* Rector ([e3c3eb9](https://github.com/achyutkneupane/laravel-hls/commit/e3c3eb95bd284fb5e669e862aa14237c9d20f26c))
* Rector replacement ([3f31e2f](https://github.com/achyutkneupane/laravel-hls/commit/3f31e2f0bf2ab55be55fa02d1642f4aa22abc865))

## v0.0.1 - 2025-07-05

### [0.0.1](https://github.com/achyutkneupane/laravel-hls/compare/v0.0.0...v0.0.1) (2025-07-05)
