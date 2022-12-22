<?php declare(strict_types=1);

namespace App\Validation;

use App\Database;
use App\FormRequests\CryptoFormRequest;
use App\FormRequests\TransferCryptoFormRequest;
use App\Models\User;
use App\Repositories\Crypto\CryptoRepository;
use App\Repositories\ShortSell\ShortSellRepository;
use App\Repositories\UserCrypto\UserCryptoRepository;
use App\Repositories\Users\UserRepository;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator as validator;

class Rules
{
    private UserRepository $userRepository;
    private CryptoRepository $cryptoRepository;
    private UserCryptoRepository $userCryptoRepository;
    private ShortSellRepository $shortSellRepository;

    public function __construct(UserRepository       $userRepository,
                                CryptoRepository     $cryptoRepository,
                                UserCryptoRepository $userCryptoRepository,
                                ShortSellRepository  $shortSellRepository)
    {
        $this->userRepository = $userRepository;
        $this->cryptoRepository = $cryptoRepository;
        $this->userCryptoRepository = $userCryptoRepository;
        $this->shortSellRepository = $shortSellRepository;
    }

    protected function validateValueGreaterThanZero(float $value, string $errorName = 'amount'): void
    {
        $valueValidator = validator::floatVal()->positive();
        try {
            $valueValidator->check($value);
        } catch (ValidationException $exception) {
            $this->addError($errorName, 'Amount must be greater than zero.');
        }
    }

    private function addError(string $name, string $message): void
    {
        $_SESSION['errors'][$name] = $message;
    }

    protected function validateUserName(string $name): void
    {
        $userNameValidator = validator::alpha()->length(3, 15);
        try {
            $userNameValidator->check($name);
        } catch (ValidationException $exception) {
            $this->addError('name', $exception->getMessage());
        }
    }

    protected function validateEmail(string $email): void
    {
        $emailValidator = validator::email();
        try {
            $emailValidator->check($email);
        } catch (ValidationException $exception) {
            $this->addError('email', $exception->getMessage());
        }
    }

    protected function validateEmailExists(string $email): void
    {
        $user = $this->userRepository->getByEmail($email);

        if ($user) {
            $this->addError('email', 'this email is already taken!');
        }
    }

    protected function validatePassword(string $password, string $errorName = 'password'): void
    {
        $passwordValidator = validator::alnum()->length(5);
        try {
            $passwordValidator->check($password);
        } catch (ValidationException $exception) {
            $this->addError($errorName, $exception->getMessage());
        }
    }

    protected function validateEqualPasswords(string $firstPassword, string $secondPassword): void
    {
        $equalPasswordValidator = validator::identical($firstPassword);
        try {
            $equalPasswordValidator->check($secondPassword);
        } catch (ValidationException $exception) {
            $this->addError('passwordConfirm', $exception->getMessage());
        }
    }

    protected function validateLoginCredentials(string $email, string $password): ?User
    {
        $user = $this->userRepository->getByEmail($email);
        if ($user) {
            if (password_verify($password, $user->getPassword())) {
                return $user;
            }
        }

        $this->addError('email', 'wrong email or password');
        return null;
    }

    protected function validateCorrectPassword(string $password, int $userId): void
    {
        $user = $this->userRepository->getById($userId);
        if ($user) {
            if (!password_verify($password, $user->getPassword())) {
                $this->addError('password', 'wrong password');
            }
            return;
        }
        $this->addError('password', 'something went wrong!');
    }

    protected function validateMoneyWithdrawal(float $amount, int $userId): void
    {
        $user = $this->userRepository->getById($userId);
        if ($amount > $user->getBalance()) {
            $this->addError('amount', 'you do not have enough money!');
        }
    }

    protected function validateBuyCrypto(CryptoFormRequest $request, string $errorName = 'buyError'): void
    {
        $user = $this->userRepository->getById($request->getUserId());
        $coin = $this->cryptoRepository->getCoin($request->getCoinId());
        $total = $coin->getPrice() * $request->getAmount();
        if ($total > $user->getBalance()) {
            $this->addError($errorName, 'you do not have enough money!');
        }
    }

    protected function validateSellCrypto(CryptoFormRequest $request, string $errorName = 'sellError'): void
    {
        $user = $this->userRepository->getById($request->getUserId());
        $userCoin = $this->userCryptoRepository->get($user->getId(), $request->getCoinId());

        if (!$userCoin || $request->getAmount() > $userCoin->getAmount()) {
            $this->addError($errorName, 'invalid request!');
        }
    }

    protected function validateTransferCrypto(TransferCryptoFormRequest $request): void
    {
        $user = $this->userRepository->getById($request->getUserId());
        $userCoin = $this->userCryptoRepository->get($user->getId(), $request->getCoinId());
        $recipient = $this->userRepository->getById($request->getRecipientId());

        if (!$recipient) {
            $this->addError('recipient', 'invalid recipient!');
        }

        if (!$userCoin || $request->getAmount() > $userCoin->getAmount()) {
            $this->addError('amount', 'invalid amount!');
        }
    }

    protected function validateBuyBackCrypto(CryptoFormRequest $request): void
    {
        $user = $this->userRepository->getById($request->getUserId());
        $openOrder = $this->shortSellRepository->getOpenOrder($user->getId(), $request->getCoinId());

        if (!$openOrder || $request->getAmount() > $openOrder->getQuantity()) {
            $this->addError('buyBack', 'invalid request!');
        }
    }
}
